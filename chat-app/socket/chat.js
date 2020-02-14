const jwtAuth = require('jsonwebtoken');
const model = require('../repositories/model');
var cron = require('node-cron');
let tutors = {};
let students = {};
let admins = {};
let subscribedStudents = {};
let rejectStudents = {};
let practiceStudents={};
module.exports = function (io) {

    io.use((socket, next) => {
        if (socket.handshake.query && socket.handshake.query.token) {
            jwtAuth.verify(socket.handshake.query.token, process.env.SECRET_KEY, function (err, decoded) {
                if (err) return next(new Error('Authentication error'));
                socket.decoded = decoded;
                next();
            });
        } else {
            next(new Error('Authentication error'));
        }
    }).on('connection', (socket) => {
        socket.on('add-user', (data) => {
            let user = socket.decoded;
            user.socket = socket.id;
            socket.emit('save-socket', socket.id);
            global.clients[socket.id] = user;

            if (user.userType === 'student') {

                students[socket.id] = user;
                model.getStudentSession(user.sub, user.subject, (studentSession) => {
                    if (studentSession) {
                        let minstudents = [];
                        for (let i in tutors) {
                            let tutor = tutors[i];
                            let studentNo = tutor.hasOwnProperty('subscribedStudents') ? tutor.subscribedStudents.length : 0;
                            minstudents.push(tutor.subscribedStudents.length)
                        }
                        let minStudentNo = Math.min(...minstudents)
                        for (let i in tutors) {
                            var tutor = tutors[i];
                            user.student = socket.id;
                            if (tutor.subject == user.subject && tutor.sub == studentSession.tutor_id && tutor.subscribedStudents.length <= minStudentNo) {
                                io.sockets.connected[i].emit('class-join-req', user);
                            }
                        }
                    } else {
                        //notify tutor
                        for (let i in tutors) {
                            if (tutors[i].subject == user.subject) {
                                io.sockets.connected[i].emit('notify-tutor', user);
                            }
                        }
                    }
                });

                //notify admins about teachers
                for (let i in admins) {
                    io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students });
                }
            } else if (user.userType === 'tutor') {

                user.subscribedStudents = [];
                tutors[socket.id] = user;
                model.saveTutorSession(user.sub, user.subject);

                model.getTutorSession(user.sub, user.subject, (tutorSession) => {

                    if (tutorSession) {


                        for (let j in students) {
                            let student = students[j];
                            if (session.subject_id == student.subject && !student.tutor) {
                                student.student = j;
                                student.socket = j;
                                socket.emit('class-join-req', student);
                                break;
                            }
                        }
                    }

                });

                //notify students about teacher

                for (let i in students) {
                    if (students[i].subject == user.subject) {
                        io.sockets.connected[i].emit('notify-student', user);
                    }
                }

                //notify admins about teachers
                for (let i in admins) {
                    // console.log(tutors);
                    io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students ,practiceStudents:practiceStudents});
                }
            } else if (user.userType == 'admin') {
                admins[socket.id] = user;
                // console.log({tutors:tutors,students:students});
                socket.emit('update-users', { tutors: tutors, students: students,practiceStudents:practiceStudents });
            }
        });


        //emit the update event to get the list of online users
        socket.on('get-online-users', (type) => {
            let user = socket.decoded;
            //send tutoros list to students
            if (type === 'tutors' && user.userType === 'student') {
                //detect subject and send the appropriate teachers
                let subjetTutors = [];
                for (let i in tutors) {
                    if (tutors[i].subject == user.subject) {
                        subjetTutors.push({ socket: i, user: tutors[i] });
                    }
                }
                socket.emit('update-online-tutors', subjetTutors);
            } else if (type === 'students' && user.userType === 'tutor') {

                let subjetStudents = [];
                for (let i in students) {
                    if (students[i].subject == user.subject) {
                        subjetStudents.push({ socket: i, user: students[i] });
                    }
                }
                //send students list to tutor
                socket.emit('update-online-students', subjetStudents);
            }
            socket.emit('update-online-users', global.clients);
        });

        socket.on('private-mesage', (data) => {

            let receiver = global.clients[data.receiver];
            if (receiver) {
                let user = socket.decoded;
                data.socket = socket.id;
                if (data.groupMode == true) {
                    if (user.userType == 'student') {
                        for (let i in tutors) {
                            if (tutors[i].subject == user.subject) {
                                let tutor = tutors[i];
                                data.senderName = user.Name;
                                data.receiverName = tutor.Name;
                                io.sockets.connected[i].emit("new-message", data);
                                model.saveMessage(data.message, user.sub, tutor.sub, user.userType, user.Name, data.session_id);
                                for (let j in tutor.subscribedStudents) {
                                    let student = tutor.subscribedStudents[j];
                                    data.senderName = user.Name;
                                    data.receiverName = student.Name;
                                    if (global.clients.hasOwnProperty(student.socket)) {
                                        io.sockets.connected[student.socket].emit("new-message", data);
                                        model.saveMessage(data.message, user.sub, student.sub, user.userType, user.Name, data.session_id);
                                    }
                                }
                            }
                        }
                    } else if (user.userType == 'tutor') {
                        let tutor = tutors[user.tutor];
                        for (let j in tutor.subscribedStudents) {
                            let student = tutor.subscribedStudents[j];
                            data.senderName = user.Name;
                            data.receiverName = student.Name;
                            if (global.clients.hasOwnProperty(student.socket)) {
                                io.sockets.connected[student.socket].emit("new-message", data);
                                model.saveMessage(data.message, user.sub, student.sub, user.userType, user.Name, data.session_id);
                            }

                        }
                    }
                } else {
                    data.receiverName = receiver.Name;
                    data.senderName = user.Name;
                    io.sockets.connected[data.receiver].emit("new-message", data);
                    model.saveMessage(data.message, user.sub, receiver.sub, user.userType, user.Name, data.session_id);
                }

            }
        });

        socket.on('req-for-join-class', (data) => {
            let tutorSocket = data.tutor;
            let student = socket.decoded;
            student.student = socket.id;
            let minstudents = [];
            for (let i in tutors) {
                let tutor = tutors[i];
                let studentNo = tutor.hasOwnProperty('subscribedStudents') ? tutor.subscribedStudents.length : 0;
                minstudents.push(tutor.subscribedStudents.length)
            }
            let minStudentNo = Math.min(...minstudents)
            for (let i in tutors) {
                var tutor = tutors[i];
                if (tutor.subject == student.subject && tutor.subscribedStudents.length <= minStudentNo) {
                    io.sockets.connected[i].emit('class-join-req', student);
                }
            }
            socket.emit('update-users', { tutors: tutors, students: students });
        });

        socket.on('accept-student', (data) => {

            let tutor = socket.decoded;
            var tutorSocket = socket.id;
            tutor.tutor = tutorSocket;

            if (tutors.hasOwnProperty(tutorSocket)) {
                if (students.hasOwnProperty(data.student)) {
                    let student = students[data.student];
                    student.tutor = tutorSocket;
                    subscribedStudents[data.student] = student;
                    tutors[tutorSocket].subscribedStudents.push(student);
                    students[data.student] = student;

                    socket.emit('student-req-accepted', student);
                    model.saveStudentSession(student.sub, student.subject, tutor.sub);
                    //send notification to student to confirm subscription
                    io.sockets.connected[data.student].emit('student-req-accepted', tutor);
                }
            }
            //notify admins about teachers
            for (let i in admins) {
                io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students });
            }
        });

        socket.on('force-tutor-student-map', (data) => {
            if (tutors.hasOwnProperty(data.tutor)) {
                if (global.clients.hasOwnProperty(data.student)) {
                    let student = global.clients[data.student];
                    let tutor = tutors[data.tutor];
                    student.tutor = data.tutor;
                    subscribedStudents[data.student] = student;
                    tutors[data.tutor].subscribedStudents.push(student);
                    if (students.hasOwnProperty(data.student)) {
                        students[data.student].tutor = data.tutor;
                    }
                    //send notificatio to tutor to confirm the subscription 
                    socket.emit('student-req-accepted', student);
                    //send notification to student to confirm subscription
                    io.sockets.connected[data.student].emit('student-req-accepted', tutor);
                    io.sockets.connected[data.tutor].emit('force-student-mapped', student);
                    //notify admin
                    socket.emit('update-users', { tutors: tutors, students: students });
                }
            }
        });
        socket.on('reject-student', (data) => {
            let tutor = socket.decoded;
            var tutorSocket = socket.id;
            tutor.tutor = socket.id;
            if (tutors.hasOwnProperty(tutorSocket)) {
                if (global.clients.hasOwnProperty(data.student)) {
                    let student = global.clients[data.student];
                    rejectStudents[data.student] = student;
                    io.sockets.connected[data.student].emit('student-req-rejected', tutor);
                }


            }
            //notify admins about teachers
            for (let i in admins) {
                io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students });
            }
        });



        socket.on('tutor-unsubscribed', (data) => {
            let tutorSocket = data.previousTutor;
            let user = socket.decoded;
            user.student = socket.id;
            if (tutors.hasOwnProperty(tutorSocket)) {
                for (let i in tutors[tutorSocket].subscribedStudents) {
                    let student = tutors[tutorSocket].subscribedStudents[i];
                    if (student.socket == socket.id) {
                        tutors[tutorSocket].subscribedStudents.splice(i, 1);
                    }
                }
                io.sockets.connected[tutorSocket].emit('unsubscribe-tutor', user);
            }
            model.saveStudentSessionEnd(user.sub, user.subject);
            //notify admins about teachers
            for (let i in admins) {
                io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students });
            }
        });

        socket.on('send-private-drawing', (data) => {

            let user = socket.decoded;
            if (data.groupMode == true) {
                if (user.userType == 'student') {
                    for (let i in tutors) {
                        if (tutors[i].subject == user.subject) {
                            let tutor = tutors[i];
                            data.senderName = user.Name;
                            data.receiverName = tutor.Name;
                            io.sockets.connected[i].emit("get-private-drawing", data);
                            model.saveMessage(data.message, user.sub, tutor.sub, user.userType, user.Name, data.session_id);
                            for (let j in tutor.subscribedStudents) {
                                let student = tutor.subscribedStudents[j];
                                data.senderName = user.Name;
                                data.receiverName = student.Name;
                                if (global.clients.hasOwnProperty(student.socket)) {
                                    io.sockets.connected[student.socket].emit("get-private-drawing", data);
                                }

                            }
                        }
                    }
                } else if (user.userType == 'tutor') {
                    if (user.hasOwnProperty('tutor')) {
                        let tutor = tutors[user.tutor];
                        for (let j in tutor.subscribedStudents) {
                            let student = tutor.subscribedStudents[j];
                            data.senderName = user.Name;
                            data.receiverName = student.Name;
                            if (global.clients.hasOwnProperty(student.socket)) {
                                io.sockets.connected[student.socket].emit("get-private-drawing", data);
                            }
                        }
                    }
                }
            } else {
                if (global.clients.hasOwnProperty(data.receiver)) {
                    io.sockets.connected[data.receiver].emit("get-private-drawing", data);
                }
            }

        });

        socket.on('req-student-drawing', (data) => {
            if (global.clients.hasOwnProperty(data.receiver)) {
                io.sockets.connected[data.receiver].emit("req-for-drawing-update", data);
            }
        });

        socket.on('send-public-drawing', (data) => {

            let user = socket.decoded;
            let subject = user.subject;
            for (let i in global.clients) {
                var c = global.clients[i];
                if (c.subject == subject) {
                    io.sockets.connected[i].emit("get-public-drawing", data);
                }
            }

        });

        /**
         * ========================================
         * NOTIFY ADMIN FOR NEW TECH SUPPORT MESSAGE
         * =========================================
         */

        socket.on('notify-admin-tech-support', (data) => {
            let user = socket.decoded;
            data.userType = user.userType;
            data.user_id = user.sub;
            model.saveNotificationMessage(data, (data) => {
                for (let i in admins) {
                    io.sockets.connected[i].emit('tech-notification', {data:data,user:user});
                }
            });

        });

        //send redraw command to student
        socket.on('redraw-canvas', (data) => {
            let user = socket.decoded;
            if (user.userType == 'tutor') {
                if (global.clients.hasOwnProperty(data.receiver)) {
                    io.sockets.connected[data.receiver].emit('force-redraw', data);
                }
            }
        });
        // call for assistant

        socket.on('redraw-foreign', (data) => {
            let user = socket.decoded;
            if (user.userType == 'tutor') {
                if (global.clients.hasOwnProperty(data.receiver)) {
                    io.sockets.connected[data.receiver].emit('undo-foreign', data.data);
                }
            }
        });
        socket.on('call-for-assitant', () => {
            let user = socket.decoded;
            for (let i in admins) {
                io.sockets.connected[i].emit('assistant-request', user);
            }
        });

        //forece disconnect
        socket.on('client-disconnect', () => {
            let user = socket.decoded;
            if (user.userType == 'student') {
                student = students[socket.id];
                if (student && student.tutor) {
                    model.saveStudentSessionEnd(user.sub, user.subject);
                }
            } else if (user.userType == 'tutor') {
                model.saveTutorSessionEnd(user.sub, user.subject);
            }
            // user.socket = socket.id;
            // if(global.clients.hasOwnProperty(socket.id)){
            //     delete global.clients[socket.id];
            // }

            // if(admins.hasOwnProperty(socket.id)){
            //     delete admins[socket.id];
            // }
            // if(tutors.hasOwnProperty(socket.id)){
            //     delete tutors[socket.id];
            //     io.sockets.emit('tutor-disconnect',{socket:socket.id,userName:user.Name,user:user});
            // }

            // if(students.hasOwnProperty(socket.id)){
            //     delete students[socket.id];
            //     io.sockets.emit('student-disconnect',{socket:socket.id,userName:user.Name,user:user});
            // }
            //  //notify admins about teachers
            //  for(let i in admins){
            //     io.sockets.connected[i].emit('update-users', {tutors:tutors,students:students});
            //    }
            socket.disconnect();

        });
        //Removing the socket on disconnect
        socket.on('disconnect', () => {
            let user = socket.decoded;
            if (user.userType == 'student') {
                student = students[socket.id];
                if (student && student.tutor) {
                    model.saveStudentSessionEnd(user.sub, user.subject);
                }

            } else if (user.userType == 'tutor') {
                model.saveTutorSessionEnd(user.sub, user.subject);
            }
            user.socket = socket.id;
            if (global.clients.hasOwnProperty(socket.id)) {
                delete global.clients[socket.id];
            }

            if (admins.hasOwnProperty(socket.id)) {
                delete admins[socket.id];
            }
            if (tutors.hasOwnProperty(socket.id)) {
                delete tutors[socket.id];
                io.sockets.emit('tutor-disconnect', { socket: socket.id, userName: user.Name, user: user });
            }

            if (students.hasOwnProperty(socket.id)) {
                delete students[socket.id];
                io.sockets.emit('student-disconnect', { socket: socket.id, userName: user.Name, user: user });
            }

            if(practiceStudents.hasOwnProperty(socket.id)){
                delete practiceStudents[socket.id];
            }
            //notify admins about teachers
            for (let i in admins) {
                io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students,practiceStudents:practiceStudents });
            }

        });

        //enabble group mode
        socket.on('group-mode-enabled', (data) => {
            let user = socket.decoded;
            if (user.userType == 'tutor') {
                for (let j in user.subscribedStudents) {
                    let student = user.subscribedStudents[j];
                    if (global.clients.hasOwnProperty(student.socket)) {
                        io.sockets.connected[student.socket].emit("group-mode-enabled", data);
                        model.saveStudentSession(student.sub, student.subject, user.sub);
                    }
                }
            }
        })
        socket.on('connect-practice-mode',()=>{
            practiceStudents[socket.id]=socket.decoded;
            for (let i in admins) {
                io.sockets.connected[i].emit('update-users', { tutors: tutors, students: students,practiceStudents:practiceStudents });
            }
        });
    
        cron.schedule('* * * * *', () => {
            for(let i in students){
                let student = students[i];
                model.getStudentSession(user.sub,user.subject,(data)=>{
                    if(data){
                        io.socket.connected[i].emit('session-notice',data);
                    }
                });
            }
    
            for(let i in tutors){
                let tutor = tutors[i];
                model.getTutorSession(tutor.sub,tutor.subject,(data)=>{
                    if(data){
                        io.socket.connected[i].emit('session-notice',data);
                    }
                });
            }
    
            for(let i in practiceStudents){
                let student = practiceStudents[i];
                model.getStudentSession(student.sub,student.subject,(data)=>{
                    if(data){
                        io.socket.connected[i].emit('session-notice',data);
                    }
                });
            }
        });
    });
   
};


