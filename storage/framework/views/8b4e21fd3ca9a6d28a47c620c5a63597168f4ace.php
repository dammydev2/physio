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

				<form method="post" action="ogPage1">

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
						<label for="exampleInputEmail1">Patient Name</label>
						<input type="text" name="name" required readonly value=" <?php echo e($row->name); ?> " class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">physio Number</label>
						<input type="text" name="physio_num"  readonly value=" <?php echo e($row->physio); ?> " class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Date of birth</label>
						<input type="text" name="DOB"  required readonly value="<?php echo e($row->dob); ?>" class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Date of evaluation</label>
						<input type="date" name="dt"  required  class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Marital status</label>
						<input type="text" required="" name="marital"  placeholder="Enter Marital status" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Diagnosis</label>
						<input type="text" name="diagnosis"  placeholder="" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Duaration of marriage</label>
						<input type="text" name="duration"  placeholder="e.g. 3 years" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Address</label>
						<input type="text" name="address"  placeholder="Enter Address" required class="form-control" id="exampleInputEmail1" />
					</div>

					<input type="hidden" name="physio" value="<?php echo e(\Auth::User()->name); ?>">
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

					<input type="submit" name="" value="Continue" class="btn btn-primary">

				</form>

			</div>
		</div>


	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Ill