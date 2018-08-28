<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <h4>Tutor Session Detail</h4>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-4">
                    Name : <b>(<?php echo e($tutor->name); ?>)</b>
                </div>
                <div class="col-sm-4">
                    Email : <b><?php echo e($tutor->email); ?></b>
                </div>
                <div class="col-sm-4">
                    Subject <b><?php echo e($tutor->subject->name); ?></b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php
                        $endDate = \Carbon\Carbon::parse($session->end_time);
                        $startDate = \Carbon\Carbon::parse($session->start_time);
                        ?>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($startDate->format('d M Y H:i')); ?></td>
                        <td><?php echo e($endDate->format('d M Y H:i')); ?></td>
                        <td><?php echo e($startDate->diffInMinutes($endDate)); ?> Minutes</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>