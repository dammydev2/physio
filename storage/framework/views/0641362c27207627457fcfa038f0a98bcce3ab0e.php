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
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="Extension" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="Abduction" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="Adduction" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="IR" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td><input type="text" required="" name="issue[]" value="ER" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <th>Elbow:</th>
              <td colspan="6"></td>
            </tr>
            <tr>
              <td>Elbow Flexion<input type="text" required="" name="issue[]" value="Elbow Flexion" style="display: none;" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td>Elbow Extension<input type="text" required="" name="issue[]" style="display: none;" value="Elbow Extension" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <th>Wrist:</th>
              <td colspan="6"></td>
            </tr>
            <tr>
              <td>Wrist Flexion<input type="text" required="" name="issue[]" value="Wrist Flexion" style="display: none;" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td>Wrist Extension<input type="text" required="" name="issue[]" style="display: none;" value="Wrist Extension" readonly></td>
              <td><input type="text" required="" name="cAROM[]"></td>
              <td><input type="text" required="" name="cPROM[]"></td>
              <td><input type="text" required="" name="cend[]"></td>
              <td><input type="text" required="" name="tAROM[]"></td>
              <td><input type="text" required="" name="tPROM[]"></td>
              <td><input type="text" required="" name="tend[]"></td>
            </tr>
            <tr>
              <td colspan="6"><textarea class="form-control" required="" name="comments" placeholder="Enter comments here"></textarea></td>
            </tr>
          </table>

          <input type="submit" name="Continue" class="btn btn-primary">

        </form>

      </div>
    </div>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Library/WebServer/Documents/physio/resources/views/ort/ortFifth.blade.php ENDPATH**/ ?>                                                                                                                                                                       <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $__env->yieldContent('title'); ?></title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body