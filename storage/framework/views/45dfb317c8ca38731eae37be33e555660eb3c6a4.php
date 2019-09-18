<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">

    	<div class="panel panel-primary col-sm-6">
    		<div class="panel-heading">
    			<h4>Add New Users</h4>
    		</div>
    		<div class="panel-body">
    			<form method="post" action="<?php echo e(url('/registeruser')); ?>">

                    <?php echo csrf_field(); ?>


                    <div class="form-group has-feedback<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                        <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="Full Name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>

                        <?php if($errors->has('name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('name')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group has-feedback<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                        <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                        <?php if($errors->has('email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group has-feedback<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                        <