<?php if((sizeof($files) > 0) || (sizeof($directories) > 0)): ?>
<table class="table table-responsive table-condensed table-striped hidden-xs table-list-view">
  <thead>
    <th style='width:50%;'><?php echo e(Lang::get('laravel-filemanager::lfm.title-item')); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-size')); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-type')); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-modified')); ?></th>
    <th><?php echo e(Lang::get('laravel-filemanager::lfm.title-action')); ?></th>
  </thead>
  <tbody>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td>
        <i class="fa <?php echo e($item->icon); ?>"></i>
        <a class="<?php echo e($item->is_file ? 'file' : 'folder'); ?>-item clickable" data-id="<?php echo e($item->is_file ? $item->url : $item->path); ?>" title="<?php echo e($item->name); ?>">
          <?php echo e(str_limit($item->name, $limit = 40, $end = '...')); ?>

        </a>
      </td>
      <td><?php echo e($item->size); ?></td>
      <td><?php echo e($item->type); ?></td>
      <td><?php echo e($item->time); ?></td>
      <td class="actions">
        <?php if($item->is_file): ?>
          <a href="javascript:download('<?php echo e($item->name); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-download')); ?>">
            <i class="fa fa-download fa-fw"></i>
          </a>
          <?php if($item->thumb): ?>
            <a href="javascript:fileView('<?php echo e($item->url); ?>', '<?php echo e($item->updated); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-view')); ?>">
              <i class="fa fa-image fa-fw"></i>
            </a>
            <a href="javascript:cropImage('<?php echo e($item->name); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-crop')); ?>">
              <i class="fa fa-crop fa-fw"></i>
            </a>
            <a href="javascript:resizeImage('<?php echo e($item->name); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-resize')); ?>">
              <i class="fa fa-arrows fa-fw"></i>
            </a>
          <?php endif; ?>
        <?php endif; ?>
        <a href="javascript:rename('<?php echo e($item->name); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-rename')); ?>">
          <i class="fa fa-edit fa-fw"></i>
        </a>
        <a href="javascript:trash('<?php echo e($item->name); ?>')" title="<?php echo e(Lang::get('laravel-filemanager::lfm.menu-delete')); ?>">
          <i class="fa fa-trash fa-fw"></i>
        </a>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

<table class="table visible-xs">
  <tbody>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <td>
        <div class="media" style="height: 70px;">
          <div class="media-left">
            <div class="square <?php echo e($item->is_file ? 'file' : 'folder'); ?>-item clickable"  data-id="<?php echo e($item->is_file ? $item->url : $item->path); ?>">
              <?php if($item->thumb): ?>
              <img src="<?php echo e($item->thumb); ?>">
              <?php else: ?>
              <i class="fa <?php echo e($item->icon); ?> fa-5x"></i>
              <?php endif; ?>
            </div>
          </div>
          <div class="media-body" style="padding-top: 10px;">
            <div class="media-heading">
              <p>
                <a class="<?php echo e($item->is_file ? 'file' : 'folder'); ?>-item clickable" data-id="<?php echo e($item->is_file ? $item->url : $item->path); ?>">
                  <?php echo e(str_limit($item->name, $limit = 20, $end = '...')); ?>

                </a>
                &nbsp;&nbsp;
                
              </p>
            </div>
            <p style="color: #aaa;font-weight: 400"><?php echo e($item->time); ?></p>
          </div>
        </div>
      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
</table>

<?php else: ?>
<p><?php echo e(trans('laravel-filemanager::lfm.message-empty')); ?></p>
<?php endif; ?>
