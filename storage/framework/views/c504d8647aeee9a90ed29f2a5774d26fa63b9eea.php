<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="ogPage6">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<div class="form-group">
						<label for="exampleInputEmail1">Do you have any movement restriction?</label>
						<label>Yes <input type="radio" name="movement" value="yes"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>No <input type="radio" name="movement" value="No"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>If yes , please list:</label>
						<textarea class="form-control" name="list"></textarea>
					</div>
					<div class="form-group">
						