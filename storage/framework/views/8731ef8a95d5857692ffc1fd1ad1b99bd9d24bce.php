<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Add Report (Mental Helth)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="menPage1">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="form-group">
						<label for="exampleInputEmail1">Client</label>
						<input type="text" name="name" required readonly value="<?php echo e($row->name); ?>" class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Date of birth</label>
						<input type="text" name="DOB"  required readonly value="<?php echo e($row->dob); ?>" class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Gender</label>
						<div class="form-group">
							<label>Male: <input type="radio" required="" name="gender" value="Male"></ins></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label>Female: <input type="radio" required="" name="gender" value="Female"></ins></label>
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">ID Code</label>
						<input type="text" name="code"  required  class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Ethnicity</label>
						<div class="form-group">
							<label>Yoruba: <input type="radio" required="" name="ethnicity" value="Yoruba"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label>Igbo: <input type="radio" required="" name="ethnicity" value="Igbo"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label>Hausa: <input type="radio" required="" name="ethnicity" value="Hausa"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text"