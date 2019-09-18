<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="ogPage8">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<div class="form-group">
						<label for="exampleInputEmail1">Spouse name</label>
						<input type="text" required="" name="spouse" class="form-control">
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>age</label>
						<input type="number" required="" name="age" class="form-control" min="0">
					</div>
					<div class="form-group">
						<label>Spouse occupation</label>
						<input type="text" required="" name="occupation" class="form-control">
					</div>
					<div class="form-group">
						<label>Patients occupation</label>
						<input type="text" required="" name="p_occupation" class="form-control">
					</div>
					<div class="form-group">
						<label>Type of apartment</label>
						<select class="form-control" required="" name="apartment">
							<option value="Bungalow">Bungalow</option>
							<option value="Storey Building">Storey Building</option>
						</select>
					</div>
					<div class="form-group">
						<label>Do you smoke?</label>&nbsp;&nbsp;
						<label>Yes <input type="radio" required="" name