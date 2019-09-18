<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Medication and Communication History</div>
			<div class="panel-body">

				<form method="post" action="<?php echo e(url('/paePage8')); ?>">
					<?php echo e(csrf_field()); ?>


					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label>Please list all the medications (with specific name, dosage, frequency and route) that you are currently taking including over the co