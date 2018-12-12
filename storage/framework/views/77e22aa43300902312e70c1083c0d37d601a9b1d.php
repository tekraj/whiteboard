<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <!-- Title Page-->
    <title>Login</title>
    <!-- Fontfaces CSS-->
    <link href="<?php echo e(asset('css/font-face.css')); ?>" rel="stylesheet" media="all">
    <!-- Bootstrap CSS-->
    <link href="<?php echo e(asset('vendor/bootstrap-4.1/bootstrap.min.css')); ?>" rel="stylesheet" media="all">
    <!-- Main CSS-->
    <link href="<?php echo e(asset('css/theme.css')); ?>" rel="stylesheet" media="all">

</head>

<body class="animsition">
<div class="page-wrapper">
    <div class="page-content--bge5"  style="background:url(<?php echo e(asset('images/login.jpg')); ?>) no-repeat center;background-size:cover;">
        <div class="container">
            <div class="login-wrap" style="max-width:450px;padding-top:12vh;">
                <div class="login-content" style="background: rgba(255,255,255,0.8);">
                    <h4 class="text-center" style="margin-top: 0;padding-bottom: 14px;border-bottom: 1px solid #ccc;margin-bottom: 15px;">Whiteboard  Login</h4>
                    <div class="login-form">
                        <form action="<?php echo e($url); ?>" method="post">
                            <?php if(count($errors) > 0): ?>
                                <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                                    <ul style="list-style: none;">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="au-input au-input--full" type="password" name="password" placeholder="Password">
                            </div>
                            <div class="login-checkbox">
                                <label>
                                    <input type="checkbox" name="remember">Remember Me
                                </label>
                                <label>
                                </label>
                            </div>
                            <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">sign in</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Jquery JS-->
<script src="<?php echo e(asset('vendor/jquery-3.2.1.min.js')); ?>"></script>
<!-- Bootstrap JS-->
<script src="<?php echo e(asset('vendor/bootstrap-4.1/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/bootstrap-4.1/bootstrap.min.js')); ?>"></script>


</body>

</html>
<!-- end document-->