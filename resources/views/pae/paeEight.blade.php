<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-12" style="overflow-y: scroll;">
      <div class="panel-heading">RANGE OF MOTION</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage5">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <table class="table" border="1">
            <tr>
              <th></th>
              <th colspan="3" style="text-align: center;">L Upper extremity</th>
              <th colspan="3" style="text-align: center;">R Upper Extremity</th>
            </tr>
            <tr>
              <td></td>
              <td>AROM</td>
              <td>PROM</td>
              <td>End Feel</td>
              <td>AROM</td>
              <td>PROM</td>
              <td>End Feel</td>
            </tr>
            <tr>
              <th>Shoulder:</th>
              <td colspan="6"></td>
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="Flexion" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
           