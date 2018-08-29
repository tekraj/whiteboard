<!Doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta name="user" content="37BEC3B3-483C-4229-9953-2268EE525A6B">
    <meta name="type" content="student">
    <link rel="stylesheet" href="{{asset('painting-app/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/font-awesome/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/js-tree/themes/default/style.min.css')}}">
    <link href="{{asset('painting-app/plugins/node-waves/waves.css')}}" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="{{asset('painting-app/plugins/animate-css/animate.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('painting-app/plugins/jquery-ui/jquery-ui.min.css')}}">
    <link href="{{asset('painting-app/css/board-css.min.css')}}" rel="stylesheet">
    <link href="{{asset('painting-app/css/themes/all-themes.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('painting-app/izitoast/css/iziToast.min.css')}}">
    <link rel="stylesheet" href="{{asset('painting-app/ripple-css/ripple.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('painting-app/css/maping-screen.css')}}">
    <title>White Board</title>
</head>
<body ng-app="mappingApp" ng-controller="MappingController">

<div class="container-fluid">
    <div class="subject-panel">
        <ul class="nav nav-tabs" role="tablist" id="tab-lists">
            <li ng-repeat="subject in subjects track by $index" role="presentation" ng-class="{'active':$index==0}"><a href="#tab-@{{subject.id}}"  role="tab" data-toggle="tab" >@{{subject.name}}</a></li>
        </ul>
        <div class="tab-content" id="tab-contents" style="background: #fff;">
            <div ng-repeat="subject in subjects track by $index" role="tabpanel" class="tab-pane" ng-class="{'active':$index==0}" id="tab-@{{subject.id }}" style="height: 400px;overflow-y: auto;">
                <div class="clearfix">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tutors
                            </div>
                            <div class="panel-body">
                                <div class="card js-tutor-card" ng-repeat="tutor in tutors track by $index" ng-if="tutor.subject==subject.id"  data-id="@{{ tutor.sub }}">
                                    <div class="header bg-green"><h2 ng-bind="tutor.Name"></h2>
                                    </div>
                                    <div class="body">
                                        <ul class="list-group" style="list-style: none;">
                                            <li class="ui-state-default js-student" ng-repeat="student in tutor.subscribedStudents track by student.ObjectID" style="padding:5px;"  data-id="@{{ student.sub }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span ng-bind="student.Name" class="text-capitalize"></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Unmapped Students
                            </div>
                            <div class="panel-body">
                                <ul class="list-group" style="list-style: none;">
                                    <li class="ui-state-default js-student"  ng-repeat="student in students track by student.ObjectID" ng-if="!student.tutor && student.subject==subject.id" style="padding:5px;" data-id="@{{ student.sub }}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span ng-bind="student.Name" class="text-capitalize"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="row">--}}
        {{--<div class="col-sm-6">--}}
            {{--<div class="card">--}}
                {{--<div class="header bg-deep-orange">--}}
                    {{--<h2>--}}
                        {{--Unmapped Students <small class="pull-right">Map them with tutors.</small>--}}
                    {{--</h2>--}}
                {{--</div>--}}
                {{--<div class="body">--}}
                    {{--<div class="list-group">--}}
                        {{--<a class="list-group-item unmapped-std row" href="#" ng-repeat="student in students track by student.ObjectID" ng-if="!student.tutor">--}}
                            {{--<div class="col-sm-7">--}}
                                {{--<span ng-bind="student.Name" class="pull-left text-capitalize"></span>--}}
                                {{--<span ng-bind="student.subject" class="pull-right text-capitalize"></span>--}}

                            {{--</div>--}}
                            {{--<div class="col-sm-5">--}}
                                {{--<select class="form-control js-map-student" data-student="@{{student.socket}}">--}}
                                    {{--<option>Select tutor to map</option>--}}
                                    {{--<option ng-repeat="tutor in tutors" ng-if="tutor.subject==student.subject" value="@{{tutor.socket}}">@{{tutor.Name}}</option>--}}
                                {{--</select>--}}
                            {{--</div>--}}

                        {{--</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-6">--}}
            {{--<div class="card">--}}
                {{--<div class="header bg-blue">--}}
                    {{--<h2>--}}
                        {{--Tutors and Students--}}
                    {{--</h2>--}}
                {{--</div>--}}
                {{--<div class="body">--}}
                    {{--<div class="card js-tutor-card" ng-repeat="tutor in tutors track by tutor.ObjectID">--}}
                        {{--<div class="header bg-green"><h2 ng-bind="tutor.Name"></h2><small ng-bind="tutor.subject" class="pull-right"></small></div>--}}
                        {{--<div class="body">--}}
                            {{--<div class="list-group">--}}
                                {{--<a href="#" class="list-group-item " ng-repeat="student in tutor.subscribedStudents track by student.ObjectID">--}}
                                    {{--<span ng-bind="student.Name" class="text-capitalize"></span>--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>
<script src="{{asset('painting-app/js/jquery.min.js')}}"></script>
<script src="{{asset('painting-app/chatjs/socket.io.js')}}"></script>
<script src="{{asset('painting-app/js/proper.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('painting-app/plugins/node-waves/waves.js')}}"></script>
<script src="{{asset('painting-app/js/angular.min.js')}}"></script>
<script>
    var user = {!! $user !!};
    var subjects = {!! $subjects !!};
</script>
<script src="{{asset('painting-app/js/mapping.js')}}"></script>
</body>
</html>