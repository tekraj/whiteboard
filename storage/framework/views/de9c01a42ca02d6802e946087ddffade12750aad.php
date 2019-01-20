<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="au-card recent-report">
            <div class="au-card-inner">
                <h3 class="title-2">View Details About <?php echo e($student->name); ?> <a href="<?php echo e(url('admin/students')); ?>" class="btn btn-default pull-right"><b>Back</b></a></h3>
                <table class="table table-hover table-striped">
                    <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo e($student->name); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo e($student->email); ?></td>
                    </tr>
                    <tr>
                        <th>Contact No</th>
                        <td><?php echo e($student->contact_no); ?></td>
                    </tr>
                    <tr>
                        <th>School</th>
                        <td><?php echo e($student->school_name); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo e($student->address); ?></td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td><?php echo e(\Carbon\Carbon::parse($student->dob)->format('d M Y')); ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo e(ucfirst($student->gender)); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo e($student->status==1 ? 'Active': 'In-active'); ?></td>
                    </tr>
                    <tr>
                        <th>Photo</th>
                        <td>
                            <img src="<?php echo e(url("storage/students-profiles-{$student->profile_pic}")); ?>" alt="" class="avatar">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>