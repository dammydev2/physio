<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Add Report (ORTHOPAEDIC)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="ortPage2">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<div class="panel panel-primary  w3-card w3-sand">
						<header class="w3-container w3-blue">
							<h4>Vital sign</h4>
						</header>
						<div class="panel-body">
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Blood Pressure</label>
								<input type="text" name="BP" required class="form-control" id="exampleInputEmail1" />
							</div>
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Heart Rate</label>
								<input type="text" name="heart"  required class="form-control" id="exampleInputEmail1" />
							</div>
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Respiration</label>
								<input type="text" name="respiration"  required class="form-control" id="exampleInputEmail1" />
							</div>
						</div>
					</div>

					<div class="form-group col-sm-6">
						<label for="exampleInputEmail1">Chief Compliant</label>
						<input type="text" name="compliant"  placeholder="Enter compliant" required class="form-control" id="exampleInputEmail1" />
					</div>

					<div class="form-group col-sm-6">
						<label for="exampleInputEmail1">History of present injury</label>
						<input type="text" name="history" placeholder="History of present injury"  required  class="form-control" id="exampleInputEmail1" />
					</div>

				<div class="form-group col-sm-12" style="background: #fff;">
					<p>&nbsp;</p>
					<label for="exampleInputEmail1"><b>Past Medical History</b></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Cardiac" />cardiac &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="NIDDM/IDDM" />NIDDM/IDDM &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="CVA" />CVA &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Hypertension" />Hypertension &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Cancer" />Cancer &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Osteoporosis" />Osteoporosis &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Respiratory" />Respiratory &nbsp;&nbsp;&nbsp;
					<input type="checkbox" n