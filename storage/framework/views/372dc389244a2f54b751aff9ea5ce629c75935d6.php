<?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr>
        <td><?php echo e($session->name); ?></td>
        <td><?php echo e($session->subject); ?></td>
        <?php if($type=='student'): ?>
            <td><?php echo e($session->tutor_name); ?></td>
        <?php endif; ?>
        <td><?php echo e($session->start_time); ?></td>
        <td><?php echo e($session->end_time); ?></td>
        <td><?php echo e(\Carbon\Carbon::parse($session->start_time)->diffInMinutes($session->end_time)); ?></td>
        <td><?php echo e($session->session_id); ?></td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>