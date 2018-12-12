<?php $__env->startSection('head'); ?>
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
        <?php echo Form::open(['url' => ($admin->id>0 ? route('admins.update',$admin->id) : route('admins.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => false]); ?>

        <?php if($admin->id>0): ?>
            <input type="hidden" name="_method" value="PATCH">
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <?php echo e($admin->id > 0 ? "Edit {$admin->name}" : "Add new Admin"); ?>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('name', 'Name', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('name',$admin->name, ['class' => 'form-control','required'=>true]); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('email', 'Email', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::email('email',$admin->email, ['class' => 'form-control','required'=>true]); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('status', 'Status', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <?php echo e(Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$admin->status, ['class' => 'form-control'])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('password', 'Password', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <?php echo e(Form::password('password', ['class' => 'form-control'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">

                    <label class="col-sm-2 text-center form-control-label">Role</label>
                    <div class="col-sm-10">
                        <div class="form-check-inline form-check">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label for="role-<?php echo e($role->name); ?>" class="form-check-label " style="margin-right:20px;">
                                    <input type="radio" id="role-<?php echo e($role->name); ?>" name="role" value="<?php echo e($role->id); ?>"
                                           class="form-check-input" <?php echo e($admin->roles->contains('id',$role->id)?'checked' : ''); ?>><?php echo e($role->display_name); ?>

                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>