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
    <link rel="stylesheet" href="<?php echo e(asset('painting-app/plugins/bootstrap/css/bootstrap.min.css')); ?>">
    <link href="<?php echo e(asset('css/font-face.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/font-awesome-4.7/css/font-awesome.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/font-awesome-5/css/fontawesome-all.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/mdi-font/css/material-design-iconic-font.min.css')); ?>" rel="stylesheet" media="all">
    <link rel="stylesheet" href="<?php echo e(asset('painting-app/js-tree/themes/default/style.min.css')); ?>">
    <link href="<?php echo e(asset('painting-app/plugins/node-waves/waves.css')); ?>" rel="stylesheet"/>
    <!-- Animation Css -->
    <link href="<?php echo e(asset('painting-app/plugins/animate-css/animate.css')); ?>" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo e(asset('painting-app/plugins/jquery-ui/jquery-ui.min.css')); ?>">
    <link href="<?php echo e(asset('painting-app/css/board-css.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('painting-app/css/themes/all-themes.css')); ?>" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo e(asset('painting-app/izitoast/css/iziToast.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('painting-app/ripple-css/ripple.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('painting-app/css/maping-screen.css')); ?>">

    <link href="<?php echo e(asset('css/theme.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet" media="all">
    <title>White Board</title>
</head>
<body ng-app="mappingApp" ng-controller="MappingController" class="animsition">
<div class="page-wrapper">
    <div id="loader" class="text-center" style="display: none;">
        <img src="<?php echo e(asset('images/loader.gif')); ?>" alt="">
    </div>
    <div class="page-container" style="padding:0px 15px;">
        <header class="header-desktop" style="left:0;">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <form class="form-header" action="" method="POST">
                            <label for="">Admin </label>
                        </form>
                        <div class="header-button">
                            <div class="noti-wrap">
                                <div class="noti__item js-item-menu">
                                    <i class="zmdi zmdi-comment-more"></i>
                                    <span class="quantity">1</span>
                                    <div class="mess-dropdown js-dropdown">
                                        <div class="mess__title">
                                            <p>You have {{techSupportData.length}} news message</p>
                                        </div>
                                        <div class="mess__item">
                                            <div class="image img-cir img-40">
                                                <img src="images/icon/avatar-06.jpg" alt="Michelle Moreno" />
                                            </div>
                                            <div class="content">
                                                <h6>Michelle Moreno</h6>
                                                <p>Have sent a photo</p>
                                                <span class="time">3 min ago</span>
                                            </div>
                                        </div>
                                        <div class="mess__item">
                                            <div class="image img-cir img-40">
                                                <img src="images/icon/avatar-04.jpg" alt="Diane Myers" />
                                            </div>
                                            <div class="content">
                                                <h6>Diane Myers</h6>
                                                <p>You are now connected on message</p>
                                                <span class="time">Yesterday</span>
                                            </div>
                                        </div>
                                        <div class="mess__footer">
                                            <a href="#">View all messages</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="image">
                                        <img src="<?php echo e(asset('images/icon/avatar-01.jpg')); ?>"/>
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn" href="#"><?php echo e(auth()->user()->name); ?></a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="<?php echo e(asset('images/icon/avatar-01.jpg')); ?>"/>
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5 class="name">
                                                    <a href="#"><?php echo e(auth()->user()->name); ?></a>
                                                </h5>
                                                <span class="email"><?php echo e(auth()->user()->email); ?></span>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="<?php echo e(url('admin/admins/display')); ?>">
                                                    <i class="zmdi zmdi-account"></i>Account</a>
                                            </div>
                                            
                                            
                                            
                                            

                                        </div>
                                        <div class="account-dropdown__footer">
                                            <a href="<?php echo e(url('admin/logout')); ?>">
                                                <i class="zmdi zmdi-power"></i>Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="main-content" style="padding-top:40px;">
            <div class="subject-panel">
                <ul class="nav nav-tabs" role="tablist" id="tab-lists">
                    <li ng-repeat="subject in subjects track by $index" role="presentation"
                        ng-class="{'active':$index==0}">
                        <a href="#tab-{{subject.id}}" role="tab" data-toggle="tab">{{subject.name}}</a></li>
                </ul>
                <div class="tab-content" id="tab-contents" style="background: #fff;">
                    <div ng-repeat="subject in subjects track by $index" role="tabpanel" class="tab-pane"
                         ng-class="{'active':$index==0}" id="tab-{{subject.id }}"
                         style="height: 400px;overflow-y: auto;">
                        <div class="clearfix">
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Tutors
                                    </div>
                                    <div class="panel-body">
                                        <div class="card js-tutor-card" ng-repeat="tutor in tutors track by $index"
                                             ng-if="tutor.subject==subject.id" data-id="{{ tutor.sub }}">
                                            <div class="header bg-green"><h2 ng-bind="tutor.Name"></h2>
                                            </div>
                                            <div class="body">
                                                <ul class="list-group" style="list-style: none;">
                                                    <li class="ui-state-default js-student tutor-student"
                                                        ng-repeat="student in tutor.subscribedStudents track by student.ObjectID"
                                                        style="padding:5px;" data-student="{{ student }}"><span
                                                                class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span
                                                                ng-bind="student.Name" class="text-capitalize"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-default js-student-card">
                                    <div class="panel-heading">
                                        Unmapped Students
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group" style="list-style: none;">
                                            <li class="ui-state-default js-student"
                                                ng-repeat="student in students track by student.ObjectID"
                                                ng-if="!student.tutor && student.subject==subject.id"
                                                style="padding:5px;"
                                                data-id="{{ student }}"><span
                                                        class="ui-icon ui-icon-arrowthick-2-n-s"></span> <span
                                                        ng-bind="student.Name" class="text-capitalize"></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('painting-app/js/jquery.min.js')); ?>"></script>

<script src="<?php echo e(asset('painting-app/chatjs/socket.io.js')); ?>"></script>
<script src="<?php echo e(asset('painting-app/js/proper.min.js')); ?>"></script>
<script src="<?php echo e(asset('painting-app/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('painting-app/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('painting-app/plugins/node-waves/waves.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/animsition/animsition.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<script src="<?php echo e(asset('painting-app/js/angular.min.js')); ?>"></script>
<script>
    var user = <?php echo $user; ?>;
    var subjects = <?php echo $subjects; ?>;
</script>
<script src="<?php echo e(asset('painting-app/js/mapping.js')); ?>"></script>
</body>
</html>