'use strict';
angular.module('mappingApp', [])
    .filter('getTimeInterval', function(){
        return function(date){
            var currerntDate = new Date();
            var oldDate = new Date(date);
           return (currerntDate.getTime()-oldDate.getTime())/(60*1000);
        }
    })
    .controller('MappingController', function ($scope, $http) {
        var socket;
        var loader = $('#loader');
        $scope.tutors = [];
        $scope.admin = {};
        $scope.user = {};
        $scope.students = {};
        $scope.practiceStudents = {};
        $scope.techSupportData = technicalMessages;
        $scope.techNotifications = 0;
        $scope.user = user;
        $scope.subjects = subjects;
        var connectionOptions = {
            'force new connection': true,
            'reconnectionAttems': "Infinity",
            "timeout": 10000,
            "transports": ["websocket"],
            "query": {"token": $scope.user.token}
        };
        socket = new io(chatUrl, connectionOptions);

        socket.emit('add-user');
        socket.on('update-users', function (data) {
            $scope.students = data.students;
            $scope.tutors = data.tutors;
            $scope.practiceStudents = data;
            $scope.$apply();
            loader.hide();
            $(".list-group").sortable({

                stop: function (event, ui) {
                    var element = $(ui.item);
                    var position = ui.position;
                    var letfMove = position.left;
                    var topMove = position.top;
                    var parent;
                    if (element.hasClass('tutor-linked')) {
                        parent = element.closest('.js-tutor-card');
                    } else {
                        parent = element.closest('.js-student-card');
                    }

                    var closestTab = element.closest('.tap-pane');
                    var parentOffset = parent.offset();
                    var parentWidth = parent.width();
                    var parentHeight = parent.height();
                    var absLeft = 0;
                    var absTop = 0;
                    if (letfMove > 1)
                        absLeft = parentOffset.left + parentWidth + letfMove;
                    else
                        absLeft = parentOffset.left + letfMove;

                    if (topMove > 1)
                        absTop = parentOffset.top + parentHeight + topMove;
                    else
                        absTop = parentOffset.top + topMove;

                    closestTab.find('.js-tutor-card').each(function (elem) {
                        if (!element.is(parent)) {
                            var elementPosition = elem.offset();
                            var elemWidth = elem.width();
                            var elemHeight = elem.height();
                            if (absTop >= elemPosition.top && absTop <= elemPosition.top + elemHeight && absLeft >= elemPosition.left && absLeft <= elemPosition.left + elemWidth) {
                                loader.show();
                                var student = element.data().student;
                                socket.emit('force-tutor-student-map', student);
                                element.remove();
                            }
                        }
                    });
                }
            });
            $(".list-group").disableSelection();
        });

        socket.on('assistant-request', function (user) {
            iziToast.warning({
                title: 'Help!',
                message: user.Name + ' Needs Help! Please send him message',
                position: 'center'
            });
        });
        socket.on('tech-notification', function (data) {
            $scope.techSupportData = data.data;
            $scope.techNotifications = $scope.techNotifications + 1;
            $scope.$apply();
            iziToast.warning({
                title: 'New Message',
                message: ($scope.techSupportData.length > 0 ? $scope.techSupportData[0].message : '') + ' - ' + data.user.Name,
                position: 'center'
            });
        });

        $(document).on('change', '.js-map-student', function () {
            var tutor = $(this).val();
            var student = $(this).data().student;
            if (tutor != '') {
                socket.emit('force-tutor-student-map', {tutor: tutor, student: student});
            }
        });

        $('.js-unread-notifications').click(function (e) {
            e.preventDefault();
            console.log('test');
            $scope.techNotifications = 0;
            $http.get(base_url + '/admin/unread-notifications');
        })


    });
