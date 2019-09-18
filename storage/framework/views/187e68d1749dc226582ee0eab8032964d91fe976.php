<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Add Report (Mental Helth)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="fitPage1">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					<?php
					$_age = floor((time() - strtotime($row->dob)) / 31556926);
					?>

					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>NAME  OF CLIENT</b></label>
						<input class="w3-input w3-border form-control w3-round" value="<?php echo e($row->name); ?>" readonly name="name" required="" type="text">
						<!--input type="text" name="rec" value="100000013"-->
					</div>
					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>Age</b></label>
						<input class="w3-input w3-border form-control w3-round" name="age" type="num" value="<?php echo e($_age); ?>" readonly="" required="" >
					</div>
					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>Date of Birth</b></label>
						<input class="w3-input w3-border form-control w3-round" value="<?php echo e($row->dob); ?>" readonly name="dob" required="" type="date">
					</div>
					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>Present Complaint</b></label>
						<input class="w3-input w3-border form-control w3-round" name="complaint" required="" type="text">
					</div>
					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>Date of Assessment</b></label>
						<input class="w3-input w3-border form-control w3-round" name="date" required="" type="date">
					</div>

					<div class="col-md-4 form-group" >
						<label class="w3-text-black"><b>History Source</b></label>
						<input class="w3-input w3-border form-control w3-round" name="history" required="" type="text">
					</div>

					<div class="col-md-12" >
			