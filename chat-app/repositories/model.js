let express = require('express');
let db = require('../repositories/db');
let jwt = require('jsonwebtoken');
let moment = require('moment');
exports.getUsers = function (userType, callback) {
    let query = "SELECT  uuid as ObjectID,name as Name FROM " + db.studentTable;
    if (userType == 'tutor') {
        query = "SELECT  uuid as ObjectID,name as Name FROM " + db.tutorTable;
    } else if (userType == 'admin') {
        query = "SELECT  uuid as ObjectID,name as Name FROM " + db.adminTable;
    } else if (userType == 'subadmin') {
        query = "SELECT  uuid as ObjectID,name as Name FROM " + db.subadminTable;
    }

    db.getData(query, function (response) {
        if (!response)
            return callback(false);
        return callback(response);
    });
};

exports.saveMessage = (message, fromUser, toUser, userType, userName, sessionId) => {
    let query = `INSERT INTO ${db.messageTable}(from_id,to_id,user_type,user_name,message,created_at,updated_at,session_id)
   VALUES (${fromUser},${toUser},'${userType}','${userName}','${message}',now(),now(),${sessionId})`;
    db.getData(query, function (response) {
        // console.log(response);
    });
    return true;
};

exports.getChattingMessages = (fromUser, toUser, userType, callback) => {
    let query = `SELECT  uc.*,t.name AS tutorName,s.name as studentName,s.uuid as Studentuuid
    FROM ${db.messageTable} AS uc
    INNER JOIN ${db.studentTable} AS s ON s.uuid = (
    CASE
    WHEN uc.user_type='student'  THEN
         uc.from_id
       ELSE 
       uc.to_id
       END
       )
       AND 
       s.uuid = 
            (CASE WHEN '${userType}'='student' THEN
              '${fromUser}'
            ELSE 
            '${toUser}'
            END)
    INNER JOIN ${db.tutorTable} AS t ON t.uuid = (
        CASE
        WHEN uc.user_type= 'tutor' THEN
        uc.from_id
        ELSE
       uc.to_id
        END 
    )
    AND  t.uuid = 
    (CASE WHEN '${userType}'='student' THEN
      '${toUser}'
    ELSE 
   '${fromUser}'
    END) LIMIT 100
    `;

    db.getData(query, function (response) {
        return callback(response);
    });
};
exports.saveStudentSession = (student_id, subject_d, tutor_id) => {
    let now = moment().format('YYYY-MM-DD hh:mm:ss');
    let query = `INSERT INTO student_sessions (student_id,subject_id,tutor_id,start_time,created_at,updated_at) VALUES (${student_id},${subject_d},${tutor_id},'${now}','${now}','${now}')`;
    db.getData(query, (response) => {

    })
    return true;
}

exports.saveTutorSession = (tutor_id, subject_d) => {
    let now = moment().format('YYYY-MM-DD hh:mm:ss');
    let query = `INSERT INTO tutor_sessions (tutor_id,subject_id,start_time,created_at,updated_at) VALUES (${tutor_id},${subject_d},'${now}','${now}','${now}')`;
    db.getData(query, (response) => {

    });
    return true;
}

exports.saveStudentSessionEnd = (student_id, subject_id) => {
    let now = moment().format('YYYY-MM-DD hh:mm:ss');
    let query = `UPDATE student_sessions set end_time = '${now}' WHERE student_id = ${student_id} AND subject_id=${subject_id} ORDER BY id desc limit 1`;
    db.getData(query, (response) => {
        //console.log(response);
    })
    return true;
}
exports.saveTutorSessionEnd = (tutor_id, subject_id) => {
    let now = moment().format('YYYY-MM-DD hh:mm:ss');
    let query = `UPDATE tutor_sessions set end_time = '${now}' WHERE tutor_id = ${tutor_id} AND subject_id=${subject_id} ORDER BY id desc limit 1`;
    db.getData(query, (response) => {

    })
    return true;
}

exports.getStudentSession = (userId, subjectId, callback) => {

    let query = `SELECT s.tutor_id,s.subject_id FROM schedules AS s 
        INNER JOIN schedule_student AS sc ON s.id = sc.schedule_id 
        where sc.student_id = ${userId} AND s.subject_id=${subjectId} AND s.schedule_start_time BETWEEN DATE_SUB( now() , INTERVAL 15 MINUTE) AND DATE_ADD( now(), INTERVAL 45 MINUTE) group by s.id ORDER BY s.schedule_start_time DESC LIMIT 1`;

    db.getData(query, function (response) {
        if (!response)
            return callback(false);
        if (response.length > 0) {
            return callback(response[0]);
        }
        return callback(false);
    });
}

exports.getTutorSession = (tutorId, subjectId, callback) => {
    let query = `SELECT sc.student_id,s.subject_id FROM schedules AS s 
    INNER JOIN schedule_student AS sc ON s.id = sc.schedule_id 
    where s.tutor_id = ${tutorId} AND s.subject_id=${subjectId} AND s.schedule_start_time BETWEEN DATE_SUB( now() , INTERVAL 15 MINUTE) AND DATE_ADD( now(), INTERVAL 15 MINUTE) GROUP BY s.id ORDER BY s.schedule_start_time DESC LIMIT 1`;

    db.getData(query, (response) => {
        if (!response)
            return callback(false);
        if (response.length > 0) {
            return callback(response[0]);
        }
        return callback(false);
    });


}

exports.saveNotificationMessage = (data, callback) => {
    let query = `INSERT INTO tech_support_messages (message,user_type,user_id,created_at,updated_at) VALUES('${data.message}','${data.userType}',${data.user_id},now(),now())`;
    db.getData(query, (response) => {
        if (!response) {
            return callback(false);
        }
        db.getData(`SELECT message,user_type, CASE WHEN user_type='student' THEN (select name from students where id=user_id LIMIT 1) ELSE (select name from tutors where id = user_id) END as user_name, created_at FROM tech_support_messages  ORDER BY id DESC LIMIT 5`, (response) => {
            return callback(response);
        });
    });
}

let encodeHtml = (str) => {
    try {
        let entityPairs = [
            { character: '&', html: '&amp;' },
            { character: '<', html: '&lt;' },
            { character: '>', html: '&gt;' },
            { character: "'", html: '&apos;' },
            { character: '"', html: '&quot;' },
        ];


        entityPairs.forEach(pair => {
            let reg = new RegExp(pair.character, 'g');
            if (pair.hasOwnProperty('html') && pair.html && pair.html != '') {
                str = str.replace(reg, pair.html);
            }

        });
        return str;
    } catch(err){
        return str;
    }
}