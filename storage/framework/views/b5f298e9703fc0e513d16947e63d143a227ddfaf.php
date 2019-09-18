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

				<form method="post" action="ogPage5">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<div class="form-group">
						<label for="exampleInputEmail1">How would you classify your general health?</label>
						<label>Good: <input type="radio" required="" name="health" value="Good" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Fair: <input type="radio" required="" name="health" value="Fair" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Poor: <input type="radio" required="" name="health" value="Poor" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nb