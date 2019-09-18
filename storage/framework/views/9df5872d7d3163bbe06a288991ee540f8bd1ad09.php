<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-6">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage12">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <div class="form-group">
            <label for="exampleInputEmail1">Endurance</label>
            <input type="text" class="form-control" name="endurance"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <table class="table" border="1">
            <tr>
              <th colspan="2" class="text-center">Special Tests</th>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Slump Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Straight Leg Raise Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Cram Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Sign of the Buttock Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Prone Knee Bending Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Valsalva’s Maneuver Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Segmental Instability Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Anterior Lumbar Instability Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="One-legged Standing Lumbar Extension Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Quadrant Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Trendelenberg Test" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Compression / Distraction" readonly></td>
              <td>
                <select name="answer[]">
                  <option value="+ve">+ve</option>
                  <option value="-ve">-ve</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><input type="text" class="form-control" name="test[]" value="Other special test" readonly></td>
              <td>
                <input type="text" value="*" class="form-control" name="answer[]" class="form-control">
              </td>
            </tr>

          </table>

          <input type="submit" name="Continue" class="btn btn-primary">

        </form>

      </div>
    </div>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?ph