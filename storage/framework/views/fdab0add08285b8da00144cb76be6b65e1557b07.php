<?php if(count($allSchedules)>0): ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>SN</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Subject</th>
            <th>Students</th>
        </tr>
        </thead>
        <tbody>

        <?php $__currentLoopData = $allSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($key+1); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($schedule->schedule_start_time)->format('d M Y H:i')); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($schedule->schedule_end_time)->format('d M Y H:i')); ?></td>
                <td><?php echo e($schedule->subject->name); ?></td>
                <td>
                    <?php $__currentLoopData = $schedule->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="label label-primary"><?php echo e($student->name); ?></label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>
    </table>
<?php else: ?>
    <h4 class="text-center">No Schedules for this day.</h4>
<?php endif; ?>