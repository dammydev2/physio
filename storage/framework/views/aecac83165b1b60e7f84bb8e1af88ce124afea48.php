<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">

        <div class="panel panel-primary col-sm-10">
            <div class="panel-heading">Add Report (ORTHOPAEDIC)</div>
            <div class="panel-body">

                <?php if(Session::has('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(Session::get('error')); ?>

                </div>
                <?php endif; ?>

                <form method="post" action="ortPage3">

                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php echo e(csrf_field()); ?>


                    <div class="panel panel-primary  w3-card w3-sand">
                      <header class="w3-container w3-blue">
                        <h4>Intensity pain scale</h4>
                    </header>
                    <div class="panel-body">
                        <p></p>
                        <div class="form-group col-sm-4">
                          <label for="exampleInputEmail1">At worse</label>
                          <select name="worse" class="form-control">
                            <option >0</option>
                            <option >1</option>
                            <option >2</option>
                            <option >3</option>
                            <option >4</option>
                            <option >5</option>
                            <option >6</option>
                            <option >7</option>
                            <option >8</option>
                            <option >9</option>
                            <option>10</option>  
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="exampleInputEmail1">At best</label>
                      <select name="best" class="form-control">
                        <option >0</option>
                        <option >1</option>
                        <option >2</option>
                        <option >3</option>
                        <option >4</option>
                        <option >5</option>
                        <option >6</option>
                        <option >7</option>
                        <option >8</option>
                        <option >9</option>
                        <option>10</option>   
                    </select>
                </div>

                <div class="form-group col-sm-4">
                  <label for="exampleInputEmail1">current</label>
                  <select name="current" class="form-control">
                    <option >0</option>
                    <option >1</option>
                    <option >2</option>
                    <option >3</option>
                    <option >4</option>
                    <option >5</option>
                    <option >6</option>
                    <option >7</option>
                    <option >8</option>
                    <option >9</option>
                    <option>10</option>      
                </select>
            </div>

        </div>
        <p>&nbsp;</p>
    </div>
    <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Duration</label>
      <input type="text" name="duration"  placeholder="For how long is the pain" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Aggravating Factors</label>
      <input type="text" name="aggravating" placeholder="Aggravating Factors"  required  class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Alleviating Factors</label>
      <input type="text" name="alleviating"  placeholder="Alleviating Factors" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-6">
      <label for="exampleInputEmail1">24-hour behavior of symptoms</label>
      <input type="text" name="behavior"  placeholder="24-hour behavior of symptoms" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-6">
      <label for="exampleInputEmail1">Medication/Allergies</label>
      <input type="text" name="medication"  placeholder="Medication/Allergies" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group">
      <label for="exampleInputEmail1"><b>Home Environment</b></label> <br>
      <input type="radio" name="environment"  value="Appropriate" required />Appropriate &nbsp;&nbsp;&nbsp;
      <input type="radio" name="environment"  value="Inppropriate" required />Inappropriate &nbsp;&nbsp;&nbsp;
  </div>
  <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Tests and Measures</label>
      <input type="text" name="measures"  placeholder="Tests and Measures" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Joint Clearing</label>
      <input type="text" name="joint"  placeholder="Joint Clearing" required class="form-control" id="exampleInputEmail1" />
  </div>
  <div class="form-group col-sm-4">
      <label for="exampleInputEmail1">Flexibility</label>
      <input type="text" name="flexibility"  placeholder="Flexibility" required class="form-control" id="exampleInputEmail1