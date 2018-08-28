'use strict';
angular.module('mappingApp',[])
.controller('MappingController', function($scope,$http){
    var socket;
    var herokoUrl = 'http://localhost:8000/';//'https://chatappwhiteboard.herokuapp.com/';
    $scope.tutors = [];
    $scope.admin = {};
    $scope.user = {};
    $scope.students = {};

    $scope.user = user;
    $scope.subjects = subjects;
    var connectionOptions = {
        'force new connection': true,
        'reconnectionAttems': "Infinity",
        "timeout": 10000,
        "transports": ["websocket"],
        "query": {"token": $scope.user.token}
    };
    socket = new io(herokoUrl, connectionOptions);

    socket.emit('add-user');
    socket.on('update-users', function(data){
        $scope.students = data.students;
        $scope.tutors = data.tutors;
        $scope.$apply();
    });

    socket.on('assistant-request', function(user){
        $.notify(user.Name+' Needs Help! Please send him message');
    });

    $(document).on('change','.js-map-student', function (){
       var tutor = $(this).val();
       var student = $(this).data().student;
       if(tutor!=''){
           socket.emit('force-tutor-student-map',{tutor:tutor,student:student});
       }
    });


});
