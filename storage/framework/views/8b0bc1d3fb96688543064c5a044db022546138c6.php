<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Edit patient</div>
			<div class="panel-body">

				<form method="post" action="<?php echo e(url('/editPatient')); ?>">
					<?php echo e(csrf_field()); ?>


					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>

					<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					<div class="form-group">
						<label>Name</label>
						<input type="text" value="<?php echo e($row->name); ?>" name="name" class="form-control">
					</div>

					<div class="form-group">
						<label>System number</label>
						<input type="text" name="sys_num" value="<?php echo e($row->sys_num); ?>" readonly="" class="form-control">
					</div>


					<div class="form-group">
						<label>Physio number</label>
						<input type="text" name="physio" value="<?php echo e($row->phy