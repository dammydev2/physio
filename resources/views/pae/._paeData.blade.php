<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">

    	<div class="panel panel-primary col-sm-6">
    		<div class="panel-heading">
    			<h4>Edit User</h4>
    		</div>
    		<div class="panel-body">
    			<form method="post" action="<?php echo e(url('/updateuser')); ?>">

                    <?php echo csrf_field(); ?>


                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="form-group has-feedback<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                        <input type="text" class="form-control" name="name" value="<?php echo e($row->name); ?>" placeholder="Full Name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>

                        <?php if($errors->has('name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group has-feedback<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <input type="email" class="form-control" name="email" value="<?php echo e($row->email); ?>" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                        <?php if($errors->has('email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group has-feedback<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        <?php if($errors->has('password')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('password')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group has-feedback<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        <?php if($errors->has('password_confirmation')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>User type</label>
                        <select name="type" class="form-control">
                            <option value="0">Front Desk</option>
                            <option value="1">Paediatrics</option>
                            <option value="2">Neurology</option>
                            <option value="3">Occupational Therapy</option>
                            <option value="4">Orthopaedics</option>
                            <option value="5">Fitness</option>
                            <option value="6">Woman Health(O & G)</option>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="<?php echo e($row->id); ?>">

                    <?php endforeach; $__env->popLoop(); $loop = $__