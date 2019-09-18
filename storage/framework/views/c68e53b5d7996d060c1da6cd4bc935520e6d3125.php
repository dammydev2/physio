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
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Weight Bearing</label>
                <select name="weight" class="form-control col-sm-4">
                  <option value="partial">partial</option>
                  <option value="full">full</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Assistive Device</label>
                <input type="text" name="assistive"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Assistance</label>
                <input type="text" name="assistance"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Distance</label>
                <input type="text" name="distance"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Proprioception</label>
                <input type="text" name="pro"  required class="form-control" id="exampleInputEmail1" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Clinical impression</label>
            <textarea name="clinical" class="form-control" placeholder="to write  things being done before and they cant do now"></textarea>
          </div>
          <div class="form-group">
            <h4 class="w3-blue">Treatment Plan</h4>
            <label for="exampleInputEmail1">Treatment provided during this visit</label>
            <input type="text" name="treatment"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Response to treatment</label>
            <select name="response" class="form-control">
              <option value="well tolerated">well tolerated</option>
              <option value="not tolerated">not tolerated</option>
              <option value="complain">complain</option>
            </select>
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Rehab Potential/Prognosis</label>
            <input type="text" name="rehab"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="w3-card">
            <header class="w3-container w3-blue">
              <h4>Goals</h4>
            </header>
            <div class="w3-container">
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Short Term Goals</label>
                <input type="text" name="short_goal"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Long Term Goals</label>
                <input type="text" name="long_goal"  required class="form-control" id="exampleInputEmail1" />
              </div>
            </div>
          </div>
          <!--::::TREATMENT PLAN::::-->
          <div class="w3-card">
            <header class="w3-container w3-blue">
              <h4>Treatment Plan</h4>
            </header>
            <div class="w3-container">
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Frequency</label>
                <input type="text" name="frequency"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Duration</label>
                <input type="text" name="duration"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group 