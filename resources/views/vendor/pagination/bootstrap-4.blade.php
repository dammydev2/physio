<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-10">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage9">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Joint Mobility</label>
            <input type="text" name="joint" required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Palpation</label>
            <input type="text" name="palpation"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Functional Assesment</label>
            <textarea name="functional" class="form-control" placeholder="next line action following patient response to previous treatment"></textarea>
          </div>
          <div class="panel panel-primary  w3-card w3-sand">
            <header class="w3-container w3-blue">
              <h4>Gait   </h4>
            </header>
            <div class="panel-body">
              <div class="form-group col-sm-4