<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Growth and Development</div>
			<div class="panel-body">

				<form method="post" action="<?php echo e(url('/paePage5')); ?>">
					<?php echo e(csrf_field()); ?>


					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>

					
					<div class="form-group">
						<label for="exampleInputEmail1">At what age did your child?</label>
						<!--input type="text" name="reason" required  class="form-control" id="exampleInputEmail1" /-->
					</div>
					<table class="table">
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Roll over from stomach to back" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Roll over from back to stomach" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="sit independently" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="crawl" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="walk holding unto furniture" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="walk independently" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</t