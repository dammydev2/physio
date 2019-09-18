<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading" id="printPageButton">Add Report (Mental Helth)</div>
			<div class="panel-body">

				<div style="width: 700px;">
					<div class="row">
						<button onclick="window.print()" id="printPageButton">Print</button>
						<div style="border: 1px solid #000; width: 750px; margin-left: 10px;">
							<h3 class="text-center">Federal Medical Centre, Abeokuta</h3>
							<h3 class="text-center">Occupational Therapy Initial Assessment FOR MENTAL HEALTH</h3>
							<table class="table"  border="1">



								<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php
								$_age = floor((time() - strtotime($row->dob)) / 31556926);
 //print_r($_age);
								?>

								<tr>
									<td>Client: <b> <?php echo e($row->name); ?> </b></td>
									<td>Name of Assessor: <b><?php echo e($row->accesor); ?></b></td>
								</tr>
								<tr>
									<td>Age: <b><?php echo e($_age); ?> years</b> <span>Date of birth: <b> <?php echo e($row->dob); ?></b></span></td>
									<td>Designation: <b><?php echo e($row->designation); ?></b></td>
								</tr>
								<tr>
									<td>Gender: <b><?php echo e($row->gender); ?></b></td>
			