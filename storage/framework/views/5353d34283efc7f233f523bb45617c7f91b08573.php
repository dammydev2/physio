<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-5">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage10">

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
                        <th colspan="2" class="text-center">Mobility</th>
                      </tr>
                      <tr>
                        <th colspan="2"></th>
                      </tr>
                      <tr>
                        <td>Rolling<input type="text" name="test[]" value="Rolling" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Scooting<input type="text" name="test[]" value="Scooting" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Supine to Sit<input type="text" name="test[]" value="Supine to Sit" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Sit to Stand<input type="text" name="test[]" value="Sit to Stand" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Wheel Chair use<input type="text" name="test[]" value="Wheel Chair use" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Other Deviations<input type="text" name="test[]" value="Other Deviations" readonly style="display: none;"></td>
                        <td>
                          <input type="text" value="*" name="answer[]" class="form-control" placeholder="Enter other here">
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <label>Comments</label>
                          <textarea class="form-control" required="" name="comments"></textarea>
                        </td>
                      </tr>
                    </table>
                    <div class="w3-card-4">
                      <header class="w3-container w3-blue">
                        <h5>Balance</h5>
                   