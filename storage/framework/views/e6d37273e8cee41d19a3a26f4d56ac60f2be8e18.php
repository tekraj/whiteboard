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
        <?php echo Form::open(['url' => ($subject->id>0 ? route('subjects.update',$subject->id) : route('subjects.store')), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'filter-form', 'files' => false]); ?>

        <?php if($subject->id>0): ?>
            <input type="hidden" name="_method" value="PATCH">
        <?php endif; ?>
        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-sm-6">
                        <b><?php echo e($subject->id > 0 ? "Edit {$subject->name}" : "Add new Subject"); ?></b>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="<?php echo e(url('admin/subjects')); ?>" class="btn btn-default"><b>Back</b></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo Form::label('name', 'Name*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px']); ?>

                            <div class="col-sm-9">
                                <?php echo Form::text('name',$subject->name, ['class' => 'form-control','required'=>true]); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <?php echo e(Form::label('status', 'Status*', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                            <div class="col-sm-9">
                                <?php echo e(Form::select('status',[''=>'Select','1'=>'Active','0'=>'In-active'],$subject->status, ['class' => 'form-control'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group row">
                    <?php echo e(Form::label('description', 'Description', ['class' => 'col-sm-3 text-right','style'=>'margin-top:8px'])); ?>

                    <div class="col-sm-9">
                        <?php echo e(Form::textarea('description',$subject->description, ['class' => 'form-control'])); ?>

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
    <script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description');
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>