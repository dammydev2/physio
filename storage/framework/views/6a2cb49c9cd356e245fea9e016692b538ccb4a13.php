<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">

    	<div class="panel panel-primary col-sm-10">
         <div class="panel-heading">DIAGNOSIS / REASON FOR REFERRAL</div>
         <div class="panel-body">

            <?php if(Session::has('error')): ?>
            <div class="alert alert-danger">
               <?php echo e(Session::get('error')); ?>

           </div>
           <?php endif; ?>

           <form method="post" action="neuPage2">

               <?php if($errors->any()): ?>
               <div class="alert alert-danger">
                  <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><?php echo e($error); ?></li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
              <?php endif; ?>

              <?php echo e(csrf_field()); ?>



              <div class="form-group">
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">H<sub>x</sub>PC</span>
                            <input type="text" name="hcp" class="form-control" placeholder="hcp" aria-describedby="basic-addon1" required=""/>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">PMH<sub>x</sub></span>
                            <input type="text" name="pmh" class="form-control" placeholder="pmh" aria-describedby="basic-addon1" required="" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">DH<sub>x</sub></span>
                            <input type="text" name="dh" class="form-control" placeholder="dh" aria-describedby="basic-addon1" required="" / >
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">SH<sub>x</sub></span>
                            <select name="sh" class="form-control" aria-describedby="basic-addon1" required="" >
                                <option>CHOOSE TYPE OF OCCUPATION</option>
                                <option value="lives with spouse" >LIVES WITH SPOUSE</option>
                                <option value="lives with relatives" >LIVES WITH RELATIVES</option>
                                <option value="lives aone" >LIVES ALONE</option>
                                <option value="lives with spouse and children" >ives with spouse and children</option>
                                <option value="lives with children" >ives with children</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">HABIT</span>
                            drinking<input type="checkbox" name="habit" value="drinking">&nbsp;&nbsp;&nbsp;&nbsp;
                            smoking<input type="checkbox" name="habit" value="smoking">&nbsp;&nbsp;&nbsp;&nbsp;
                            snuffing<input type="checkbox" name="habit" value="snuffing">&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 ">
                    <div class="form-group">
                        <div class="input-group w3_w3layouts col-lg-12">
                            <span class="input-group-addon" id="basic-addon1">ACCOMMODATION</span>
                            <select name="accomodation" class="form-control" placeholder="Sex" aria-describedby="basic-addon1" required="" >
                                <option>CHOOSE TYPE OF HOUSE</option>
                                <option value="bungalow" >BUNGALOW</option>
                                <option value="flat" >FLAT</option>
                                <option value="UPSTAIRS" >UPSTAIRS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mult  ">
                    <div class="form-group ">
                        <div class="form-check ">
                         <label class="form-check-label" for="exampleRadios1">Stairs ?</label>&nbsp;&nbsp;
                         <input class="form-check-input" type="radio" name="stairs" id="exampleRadios1" value="yes" required>
                         <label class="form-check-label" for="exampleRadios1">Yes</label>&nbsp;
                         <input class="form-check-input" type="radio" name="stairs" id="exampleRadios1" value="no" required>
                         <label class="form-check-label" for="exampleRadios1">No</label>
                         <div class="rail" >
                             <label class="form-check-label" for="exampleRadios1">Handrails ?</label>&nbsp;&nbsp;
                             <input class="form-check-input" type="radio" name="Handrails" id="exampleRadios1" value="0" required>
                             <label class="form-check-label" for="exampleRadios1">None</label>&nbsp;
                             <input class="form-check-input" type="radio" name="Handrails" id="exampleRadios1" value="1" required>
                             <label class="form-check-label" for="exampleRadios1">1</label>&nbsp;
                             <input class="form-check-input" type="radio" name="Handrails" id="exampleRadios1" value="2" required>
                             <label class="form-check-label" for="exampleRadios1">2</label>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-lg-6 ">
                <div class="form-group">
                    <div class="form-check">
                     <label class="form-check-label" for="exampleRadios1">Wc ?</label>&nbsp;&nbsp;
                     <input class="form-check-input" type="radio" name="wc" id="exampleRadios1" value="Upstairs" required>
                     <label class="form-check-label" for="exampleRadios1">Upstairs</label>&nbsp;
                     <input class="form-check-input" type="radio" name="wc" id="exampleRadios1" value="Downstairs" required>
                     <label class="form-check-label" for="exampleRadios1">Downstairs</label>
                 </div>
             </div>
         </div>
         <div class="col-lg-6 ">
            <div class="form-group">
                <div class="input-group w3_w3layouts col-lg-12">
                    <span class="input-group-addon" id="basic-addon1"> NUMBER OF CHILDREN</span>
                    <input type="num" name="child" class="form-control" placeholder="number of children" " aria-describedby="basic-addon1" required="" / >
                </div>
            </div>
        </div>

        <div class="col-lg-6 ">
            <div class="form-group">
                <div class="input-group w3_w3layouts col-lg-12">
                    <span class="input-group-addon" id="basic-addon1">NUMBER OF PREGNANCY</span>
                    <input type="num" name="pregnancy" class="form-control" placeholder="number of pregnancy" aria-describedby="basic-addon1" required="" / >
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="form-group">
                <div class="input-group w3_w3layouts col-lg-12">
                    <span class="input-group-addon" id="basic-addon1">NUMBER OF WIVES</span>
                    <input type="num" name="wives" class="form-control" placeholder="number of wives" aria-describedby="basic-addon1" required="" / >
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="form-group">
                <div class="input-group w3_w3layouts col-lg-12">
                    <span class="input-group-addon" id="basic