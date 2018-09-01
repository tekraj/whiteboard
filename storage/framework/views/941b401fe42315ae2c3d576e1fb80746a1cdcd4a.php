<?php $__env->startSection('head'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/jquery-ui/jquery-ui.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">

        <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php echo Form::open(['url' => ($student->id>0 ? route('students.update',$student->id) : route('students.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => true,'autocomplete'=>str_random(7)]); ?>

        <?php if($student->id>0): ?>
            <input type="hidden" name="_method" value="PATCH">
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        <?php echo e($student->id > 0 ? "Edit {$student->name}" : "Add new Tutor"); ?>

                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="<?php echo e(url('admin/students')); ?>" class="btn btn-default">Back</a>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('name', 'Name*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('name',$student->name, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('email', 'Email*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::email('email',$student->email, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('class_id', 'Class*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <?php echo e(Form::select('class_id',([''=>'Selet Class']+$classes),$student->class_id, ['class' => 'form-control','autocomplete'=>str_random(7)])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('password',  'Password*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="password" value="<?php echo e($student->password_plain); ?>" name="password" autocompleted="<?php echo e(str_random(7)); ?>" required="true" class="form-control">
                                    <label  for="edit-schedule-start-date" class="input-group-addon js-show-passwaord">
                                        <span class="fa fa-eye"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('contact_no', 'Contact No*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('contact_no',$student->contact_no, ['class' => 'form-control','required'=>true,'pattern'=>'^\d{10}$','autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('address', 'Address*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('address',$student->address, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('school_name', 'School*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('school_name',$student->school_name, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('gender', 'Gender*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::select('gender',[''=>'Select Gender','male'=>'Male','female'=>'female','others'=>'Others'],$student->gender, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)]); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Select Subjects</label>
                    <div class="col-sm-8">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label>
                                <input type="checkbox" name="subjects[<?php echo e($key); ?>]" value="<?php echo e($subject->id); ?>" <?php echo e($student->subjects->contains('id',$subject->id)?'checked':''); ?>> <?php echo e($subject->name); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('dob', 'Date Of Birth*', ['class' => 'col-sm-4 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-8">
                                <?php echo e(Form::text('dob',$student->dob, ['class' => 'form-control js-date-picker','required'=>true])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('status', 'Status*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <?php echo e(Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$student->status, ['class' => 'form-control','required'=>true,'autocomplete'=>str_random(7)])); ?>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-7">

                        <div class="form-group row">
                            <?php echo e(Form::label('profile_pics', 'Profile Picture', ['class' => 'col-sm-4 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-8">
                                <div class="card js-profile-pic-card <?php echo e(($student->id>0 && !empty($student->profile_pic)) ? '': 'hidden'); ?>">
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img class="rounded-circle mx-auto d-block js-profile-pic-img" src="<?php echo e(url("storage/students-profiles-{$student->profile_pic}")); ?>" alt="Card image cap">
                                        </div>
                                    </div>
                                </div>
                                <?php echo e(Form::file('profile_pic', ['class' => 'js-profile-pic hidden'])); ?>

                                <a href="#" class="btn btn-primary btn-sm js-sub-file-btn"> Select Profile Pic</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-dot-circle-o"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('vendor/jquery-ui/jquery-ui.js')); ?>"></script>
    <script>
        $(document).ready(function(){
            $('.js-sub-file-btn').click(function(e){
                e.preventDefault();
                $(this).siblings('.js-profile-pic').click();
            });
            $('.js-profile-pic').change( function () {
                var file = this.files[0];
                var $parent = $(this).parent();
                if (!file.name.match(/\.(jpg|jpeg|png|gif)$/)) {
                    alert('Invalid Image');
                    return false;
                }
                $parent.find('.js-profile-pic-card').removeClass('hidden');
                var reader = new FileReader();
                reader.onload = function (e) {
                    $parent.find('.js-profile-pic-img').attr('src', e.target.result);

                };
                reader.readAsDataURL(file);
            });
            $('.ui-datepicker-next,.ui-datepicker-prev').click(function(e){
                e.preventDefault();
            })
            $('.js-date-picker').datepicker({ maxDate: new Date, minDate: new Date(1970, 6, 12),dateFormat: 'yy-mm-dd',});
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>