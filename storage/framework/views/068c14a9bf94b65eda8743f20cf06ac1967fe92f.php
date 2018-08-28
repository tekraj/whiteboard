<?php $__env->startSection('content'); ?>
<?php
$endOfTheMonth = (int) \Carbon\Carbon::now()->endOfMonth()->format('d');
$url = url('admin/dashboard/get-schedule');
$month = \Carbon\Carbon::now()->format('m');
?>
<div class="container-fluid">
    <h3 style="margin-bottom:30px;"><span class="schedule"><?php echo e(\Carbon\Carbon::now()->format('F')); ?></span> Scheduling</h3>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <span style="font-size:24px;font-weight: bold" class="text-danger" id="month-holder" data-month="<?php echo e((int) \Carbon\Carbon::now()->format('m')); ?>">
                        <?php echo e(\Carbon\Carbon::now()->format('F')); ?>

                    </span>
                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-warning pull-left" data-toggle="modal" data-target="#add-schedule-modal">Add New Schedule</button>
                    <div class="btn-group">
                        <button class="btn btn-primary js-prev-next-month" id="prev-month" data-factor="-1" data-url="<?php echo e(url('admin/dashboard/get-calender')); ?>">Prev Month</button>
                        &nbsp;
                        <button class="btn btn-primary js-prev-next-month" id="next-month" data-factor="1" data-url="<?php echo e(url('admin/dashboard/get-calender')); ?>">Next Month</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" id="calender-holder">
            <?php echo $__env->make('admin.calender',compact('endOfTheMonth','url','month','allSchedules'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('extraitems'); ?>
    <div class="modal" id="add-schedule-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form data-url="<?php echo e(url('admin/dashboard/add-new-schedule')); ?>" class="card" id="add-schedule-form" style="margin-bottom:0">
                    <div class="card-header">
                        Create New Schedule
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-4">Start Time</label>
                            <div class="col-sm-8 form-group">
                                <div class='input-group date' >
                                    <?php echo e(Form::text('schedule_start_date',null,['class'=>'form-control','id'=>'schedule-start-date'])); ?>

                                    <label  for="schedule-start-date" class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label  class="col-sm-4">End Time</label>
                            <div class="col-sm-8  form-group">
                                <div class='input-group date' >
                                    <?php echo e(Form::text('schedule_end_date',null,['class'=>'form-control','id'=>'schedule-end-date'])); ?>

                                    <label  for="schedule-end-date" class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="schedule-subject" class="col-sm-4">Subject</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::select('subject_id',$subjects,null,['class'=>'form-control r','id'=>'schedule-subject','data-url'=>url('admin/dashboard/get-tutors')])); ?>

                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="schedule-tutors" class="col-sm-4">Tutor</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::select('tutor_id',[''=>'Select Tutors'],null,['class'=>'form-control','id'=>'schedule-tutors'])); ?>

                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="schedule-students" class="col-sm-4">Students</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::text('students',null,['class'=>'form-control','id'=>'schedule-students'])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right" >
                        <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="edit-schedule-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form data-url="<?php echo e(url('admin/dashboard/edit-schedule')); ?>" class="card edit" id="edit-schedule-form" style="margin-bottom:0">
                    <div class="card-header">
                        Create New Schedule
                    </div>
                    <?php echo e(Form::hidden('schedule_id',null,['id'=>'edit_schedule_id'])); ?>

                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-4">Start Time</label>
                            <div class="col-sm-8 form-group">
                                <div class='input-group date' >
                                    <?php echo e(Form::text('schedule_start_date',null,['class'=>'form-control','id'=>'edit-schedule-start-date'])); ?>

                                    <label  for="edit-schedule-start-date" class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label  class="col-sm-4">End Time</label>
                            <div class="col-sm-8  form-group">
                                <div class='input-group date' >
                                    <?php echo e(Form::text('schedule_end_date',null,['class'=>'form-control','id'=>'edit-schedule-end-date'])); ?>

                                    <label  for="edit-schedule-end-date" class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="edit-schedule-subject" class="col-sm-4">Subject</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::select('subject_id',$subjects,null,['class'=>'form-control r','id'=>'edit-schedule-subject','data-url'=>url('admin/dashboard/get-tutors')])); ?>

                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="edit-schedule-tutors" class="col-sm-4">Tutor</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::select('tutor_id',[''=>'Select Tutors'],null,['class'=>'form-control','id'=>'edit-schedule-tutors'])); ?>

                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="edit-schedule-students" class="col-sm-4">Students</label>
                            <div class="col-sm-8">
                                <?php echo e(Form::text('students',null,['class'=>'form-control','id'=>'edit-schedule-students'])); ?>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right" >
                        <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>