<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php if(session()->has('success')): ?>
            <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                <?php echo e(session('success')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <ul class="nav nav-pills" id="ow-donut">
                            <li><a href="<?php echo e(route('subjects.create')); ?>"><b>Add New</b></a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <?php echo Form::open(['url' => 'admin/subjects/search/', 'method' => 'get', 'class' => 'form-horizontal','id' => 'merchants-search-form']); ?>

                        <div class="input-group">
                            <?php echo Form::text('search_data', isset($search_data) ? $search_data : null, array('class' => 'form-control', 'placeholder' => 'Search Filters...')); ?>

                            <span class="input-group-btn"><?php echo Form::submit('Go', array('class' => 'btn append-btn btn-primary')); ?></span>
                        </div><!-- /input-group -->
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                    <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($subject->name); ?></td>
                            <td>
                                <a href="<?php echo e(route("subjects.edit",$subject->id)); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-edit large"></i>
                                </a>
                                <?php if(auth()->user()->isSuperAdmin()): ?>
                                    <form action="<?php echo e(route("subjects.destroy",$subject->id)); ?>" method="post" style="display:inline-block">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Do you want to delete this subject')"> <i class="fa fa-trash large"></i></button>
                                        <?php echo csrf_field(); ?>

                                    </form>
                                <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="pull-right link"><?php echo $subjects->render(); ?></div>
            </div>
        </div>





    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master-layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>