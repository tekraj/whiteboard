<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="au-card recent-report">
            <div class="au-card-inner">
                <h3 class="title-2">View Details About <?php echo e($tutor->name); ?> <a href="<?php echo e(url('admin/tutors')); ?>" class="btn btn-default pull-right"><b>Back</b></a></h3>
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td><?php echo e($tutor->name); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo e($tutor->email); ?></td>
                        </tr>
                        <tr>
                            <th>
                                Subject
                            </th>
                            <td><?php echo e($tutor->subject->name); ?></td>
                        </tr>
                        <tr>
                            <th>Contact No</th>
                            <td><?php echo e($tutor->contact_no); ?></td>
                        </tr>
                        <tr>
                            <th>School</th>
                            <td><?php echo e($tutor->school_name); ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo e($tutor->address); ?></td>
                        </tr>
                        <tr>
                            <th>Date Of Birth</th>
                            <td><?php echo e(\Carbon\Carbon::parse($tutor->dob)->format('d M Y')); ?></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo e(ucfirst($tutor->gender)); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo e($tutor->status==1 ? 'Active': 'In-active'); ?></td>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <td>
                                <img src="<?php echo e(url("storage/tutors-profiles-{$tutor->profile_pic}")); ?>" alt="" class="avatar">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>