'use strict';
angular.module('mappingApp',[])
.controller('MappingController', function($scope,$http){
    var socket;
    var loader = $('#loader');
    $scope.tutors = [];
    $scope.admin = {};
    $scope.user = {};
    $scope.students = {};
    $scope.techSupportData = [];
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
    socket.on('update-users', function(data){
        $scope.students = data.students;
        $scope.tutors = data.tutors;
        $scope.$apply();
        loader.hide();
        $( ".list-group" ).sortable({

            stop : function(event, ui){
                var element = $(ui.item);
                var position = ui.position;
                var letfMove = position.left;
                var topMove = position.top;
                var parent;
                if(element.hasClass('tutor-linked')){
                    parent = element.closest('.js-tutor-card');
                }else{
                    parent = element.closest('.js-student-card');
                }

                var closestTab = element.closest('.tap-pane');
                var parentOffset = parent.offset();
                var parentWidth = parent.width();
                var parentHeight = parent.height();
                var absLeft=0;
                var absTop = 0;
                if(letfMove>1)
                    absLeft = parentOffset.left + parentWidth + letfMove;
                else
                    absLeft = parentOffset.left+letfMove;

                if(topMove>1)
                    absTop = parentOffset.top+parentHeight+topMove;
                else
                    absTop = parentOffset.top + topMove;

                closestTab.find('.js-tutor-card').each(function(elem){
                    if(!element.is(parent)){
                        var elementPosition = elem.offset();
                        var elemWidth = elem.width();
                        var elemHeight = elem.height();
                        if(absTop>=elemPosition.top && absTop<=elemPosition.top+elemHeight && absLeft>=elemPosition.left &&  absLeft<=elemPosition.left+elemWidth){
                            loader.show();
                            var student = element.data().student;
                            socket.emit('force-tutor-student-map',student);
                            element.remove();
                        }
                    }
                });
        }
        });
        $( ".list-group" ).disableSelection();
    });

    socket.on('assistant-request', function(user){
        $.notify(user.Name+' Needs Help! Please send him message');
    });

    socket.on('tech-notification', function(data){
        $scope.techSupportData= data;
        $scope.techNotifications = data.length;
    });

    $(document).on('change','.js-map-student', function (){
       var tutor = $(this).val();
       var student = $(this).data().student;
       if(tutor!=''){
           socket.emit('force-tutor-student-map',{tutor:tutor,student:student});
       }
    });

    $(document).on('click','.js-unread-notifications', function(e){
        e.preventDefault();
        $scope.techNotifications =0;
        $http.get(base_url+'/utility/unread-notifications')
    })


});
