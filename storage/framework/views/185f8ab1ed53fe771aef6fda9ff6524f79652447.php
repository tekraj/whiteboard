<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <meta name ="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="<?php echo e(asset('css/font-face.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/font-awesome-4.7/css/font-awesome.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/font-awesome-5/css/fontawesome-all.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/mdi-font/css/material-design-iconic-font.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo e(asset('vendor/bootstrap-4.1/bootstrap.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo e(asset('vendor/animsition/animsition.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/wow/animate.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/css-hamburgers/hamburgers.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/slick/slick.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/select2/select2.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('vendor/perfect-scrollbar/perfect-scrollbar.css')); ?>" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo e(asset('css/theme.css')); ?>" rel="stylesheet" media="all">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/magicsuggest/magicsuggest-min.css')); ?>">
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet" media="all">

    <?php echo $__env->yieldContent('head'); ?>
</head>

<body class="animsition">
<div class="page-wrapper">
    <!-- HEADER MOBILE-->
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="index.html">
                        <img src="" alt="White Board" />
                    </a>
                    <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    <li>
                        <a class="js-arrow" href="<?php echo e(url('admin/dashboard')); ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="chart.html">
                            <i class="fas fa-chart-bar"></i>Charts</a>
                    </li>
                    <li>
                        <a href="table.html">
                            <i class="fas fa-table"></i>Tables</a>
                    </li>
                    <li>
                        <a href="form.html">
                            <i class="far fa-check-square"></i>Forms</a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fas fa-calendar-alt"></i>Calendar</a>
                    </li>
                    <li>
                        <a href="map.html">
                            <i class="fas fa-map-marker-alt"></i>Maps</a>
                    </li>

                    
                        
                            
                        
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                            
                                
                            
                        
                    
                </ul>
            </div>
        </nav>
    </header>
    <!-- END HEADER MOBILE-->

    <!-- MENU SIDEBAR-->
    <aside class="menu-sidebar d-none d-lg-block">
        <div class="logo">
            <a href="#">
                <img src="" alt="WhiteBoard Admin" />
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    <li class="<?php echo e($pageTitle=='Dashboard'?'active':''); ?>">
                        <a  href="<?php echo e(url('admin')); ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                    <li class="<?php echo e($pageTitle=='Admins'?'active':''); ?>">
                        <a href="<?php echo e(url('admin/admins')); ?>">
                            <i class="fas fa-users"></i>Manage Admins</a>
                    </li>
                    <?php endif; ?>

                    <li class="<?php echo e($pageTitle=='Tutors'?'active':''); ?>">
                        <a href="<?php echo e(url('admin/tutors')); ?>">
                            <i class="fas fa-book"></i>Manage Tutors</a>
                    </li>
                    <li class="<?php echo e($pageTitle=='Students'?'active':''); ?>">
                        <a href="<?php echo e(url('admin/students')); ?>">
                            <i class="fas fa-book"></i>Manage Students</a>
                    </li>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()): ?>
                    <li class="<?php echo e($pageTitle=='Subjects'?'active':''); ?>">
                        <a href="<?php echo e(url('admin/subjects')); ?>">
                            <i class="fas fa-book"></i>Subjects</a>
                    </li>
                    <?php endif; ?>
                    <li class="<?php echo e($pageTitle=='Mapping Board'?'active':''); ?>">
                        <a href="<?php echo e(url('admin/mapping-screen')); ?>" target="_blank">
                            <i class="fas fa-book"></i>Mapping Board</a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->

    <!-- PAGE CONTAINER-->
    <div class="page-container">
        <header class="header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <form class="form-header" action="" method="POST">
                            <label for=""><?php echo e($pageTitle); ?></label>
                        </form>
                        <div class="header-button">
                            <div class="noti-wrap">

                            </div>
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="image">
                                        <img src="images/icon/avatar-01.jpg"  />
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn" href="#"><?php echo e(auth()->user()->name); ?></a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="images/icon/avatar-01.jpg"  />
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
        <div class="main-content">
            <div class="section__content ">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

</div>
<div class="modal" id="schedule-modal">
    <div class="modal-dialog" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">All Schedule of <span class="js-schedule-date"></span></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->yieldContent('extraitems'); ?>
<!-- Jquery JS-->
<script src="<?php echo e(asset('vendor/jquery-3.2.1.min.js')); ?>"></script>
<!-- Bootstrap JS-->
<script src="<?php echo e(asset('vendor/bootstrap-4.1/popper.min.js')); ?>"></script>

<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="<?php echo e(asset('vendor/bootstrap-4.1/bootstrap.min.js')); ?>"></script>
<!-- Vendor JS       -->
<script src="<?php echo e(asset('vendor/slick/slick.min.js')); ?>">
</script>
<script src="<?php echo e(asset('vendor/wow/wow.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/animsition/animsition.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')); ?>">
</script>
<script src="<?php echo e(asset('vendor/counter-up/jquery.waypoints.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/counter-up/jquery.counterup.min.js')); ?>">
</script>
<script src="<?php echo e(asset('vendor/circle-progress/circle-progress.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/chartjs/Chart.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/magicsuggest/magicsuggest.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datetimepicker/date-time-picker.min.js')); ?>"></script>

<!-- Main JS-->
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<?php echo $__env->yieldContent('javascript'); ?>
</body>

</html>
<!-- end document-->
