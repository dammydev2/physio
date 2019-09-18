<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-6">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="ogPage2">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<div class="form-group">
						<label for="exampleInputEmail1">What are you seeking treatment for?</label>
						<input type="text" name="reason" required class="form-control" id="exampleInputEmail1" />
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>Do you have any of the following conditions?</label>
						Nausea and vomiting: <input type="checkbox" name="conditions[]" value="Nausea and vomiting">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<p class="text-center text-bold"><b>Musculoskeletal problems:</b> </p>
						Low back pain: <input type="checkbox" name="conditions[]" value="Low back pain">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Radiating pain: <input type="checkbox" name="conditions[]" value="Radiating pain">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Numbness or tingling sensations: <input type="checkbox" name="conditions[]" value="Numbness or tingling sensations">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Cramps: <input type="checkbox" name="conditions[]" value="Cramps">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<div class="form-group w3-sand ">
						<h5 class="text-center text-bold">Swellings of extremities</h5>
						<table class="table container">
							<tr>
								<td>upper limb Right: <input type="checkbox" name="limb[]" value="upper limb Right"></td>
								<td>upper limb Left: <input type="checkbox" name="limb[]" value="upper limb Left"></td>
							</tr>
							<tr>
								<td>Lower limb Right: <input type="checkbox" name="limb[]" value="Lower limb Right"></td>
								<td>Lower limb Left: <input type="checkbox" name="limb[]" value="Lower limb Left"></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<label>Frequency of micturition</label>
						<input type="text" name="frequency" class="form-control">
					</div>
					<div class="form-group">
						<h5 class="text-center">Weakness of muscles</h5>
						<table class="table">
							<tr>
								<td>Facial right: <input type="checkbox" name="muscles[]" value="Lower limb right"></td>
								<td>upper limb Right: <input type="checkbox" name="muscles[]" value="upper limb Right"></td>
	