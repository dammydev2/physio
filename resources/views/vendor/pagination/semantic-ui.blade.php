<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-9">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage13">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Physiotheraphyst Name</label>
            <input type="text" name="name" value="<?php echo e(\Auth::User()->name); ?>" readonly required class="form-control" id="exampleInputEmail1" />
          </div>

          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Completion Date</label>
            <input type="date" name="dt"  required class="form-control" id="exampleInputEmail1" />
          </div>

          <input type="submit" name="Continue" class="btn btn-primary">

        </form>

      </div>
    </div>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Sup