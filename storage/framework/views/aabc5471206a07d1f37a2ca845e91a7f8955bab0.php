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

        <form method="post" action="ortPage8">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Muscle circumference</label>
            <input type="text" name="circumference" required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Endurance</label>
            <input type="text" name="endurance"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <table class="table" border="1">
            <tr>
              <th colspan="2" class="text-center">Special Tests</th>
            </tr>
            <tr>
              <th colspan="2">Cervical</th>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Distraction Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Compression Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Spurling Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Movement Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <th colspan="2">Shoulder</th>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Empty Can Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Sulcus Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Drop Arm Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <th colspan="2">Elbow</th>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Valgus Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Varus Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Tinel Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <th colspan="2">Hand and Wrist</th>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Tinel’s Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Reverse Phalen’s Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" name="test[]" value="Compression T