<?php if(count($allSchedules)>0): ?>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>SN</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Subject</th>
        <th>Tutor</th>
        <th>Students</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    <?php $__currentLoopData = $allSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $startDate = \Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i');
            $endDate = \Carbon\Carbon::parse($schedule->schedule_end_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i')
        ?>
        <tr>
            <td><?php echo e($key+1); ?></td>
            <td><?php echo e($startDate); ?></td>
            <td><?php echo e($endDate); ?></td>
            <td><?php echo e($schedule->subject->name); ?></td>
            <td><?php echo e($schedule->tutor->name); ?></td>
            <td>
                <?php $__currentLoopData = $schedule->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="label label-primary"><?php echo e($student->name); ?></label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td>
                <?php if(\Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata') > \Carbon\Carbon::now()): ?>
                    <a href="#" class="js-edit-schedule" data-tutorurl="<?php echo e(url('admin/dashboard/get-tutors')); ?>" data-id="<?php echo e($schedule->id); ?>" data-subjectid="<?php echo e($schedule->subject->id); ?>" data-tutorid="<?php echo e($schedule->tutor->id); ?>" data-students="<?php echo $schedule->students->pluck('id'); ?>" data-startdate="<?php echo e(\Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata')->format('Y-m-d H:i')); ?>" data-enddate="<?php echo e(\Carbon\Carbon::parse($schedule->schedule_end_time,'UTC')->tz('Asia/Kolkata')->format('Y-m-d H:i')); ?>"><i class="fa fa-edit"></i></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </tbody>
</table>
<?php else: ?>
    <h4 class="text-center">No Schedules for this day.</h4>
<?php endif; ?>