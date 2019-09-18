<?php $__env->startSection('content'); ?>
<style type="text/css">
  @media  print {
      #printPageButton {
        display: none;
      }
    }

</style>

<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-9">
			<div class="panel-heading" id="printPageButton">*******</div>
			<div class="panel-body">

				<!-- Icon Cards-->
				<!-- /.container-fluid -->
				<button onclick="window.print()" id="printPageButton">Print</button>
				<div class="col-sm-12 w3-sand">
					<p>&nbsp;</p>
					<div class="row">
						<div style="width: 750px; border: 1px solid #000;">
							<h3 class="text-center">FEDERAL MEDICAL CENTRE, ABEOKUTA <br>
								PHYSIOTHERAPY DEPARTMENT<br>

								ORTHOPAEDIC UNIT<br>

							JOINT EVALUATION FORM</h3>
							<div style="padding: 20px;">

								<table class="table">
									<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

									<?php
									$_age = floor((time() - strtotime($row->dob)) / 31556926);
									?>
									<tr>
										<td>Patient Name: <b><?php echo e($row->name); ?></b></td>
										<td>DOB: <b><?php echo e($row->dob); ?></b></td>
									</tr>
									<tr>
										<td>Age: <b><?php echo e($_age); ?></b></td>
										<td>Date: <b><?php echo e($row->dt); ?></b></td>
									</tr>
									<tr>
										<td>Diagnosis: <b><?php echo e($row->diagnosis); ?></b></td>
										<td>Occupation: <b><?php echo e($row->occupation); ?></b></td>
										<!--<td>Onset: <b>2018-11-24</b></td>-->
									</tr>
									<tr>
										<td>Patient Goals: <b>Bruise to heal</b></td>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

									<?php $__currentLoopData = $data2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

									<tr>
										<td>Vital Signs (BP): <b><?php echo e($row->bp); ?></b></td>
										<td>Heart Rate: <b><?php echo e($row->heart); ?></b></td>
									</tr>
									<tr>
										<td>Respiration: <b><?php echo e($row->espiration); ?></b></td>
										<td>Chief Complaint: <b><?php echo e($row->omplaint); ?></b>
										</tr>
										<tr>
											<td>History of present injury: <b><?php echo e($row->history); ?></b></td>
											<td>Current Symptoms: <b><?php echo e($row->symptoms); ?></b></td>
										</tr>
										<tr>
											<td>Onset: <b><?php echo e($row->onset); ?></b></td>
											<td>Pain: <b><?php echo e($row->pain); ?></b></td>
										</tr>
										<tr>
											<td>Description: <b><?php echo e($row->des); ?></b></td>
										</tr>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</table>

									<div style="border: 1px solid #000; padding: 5px;">
										<p><b>PAST MEDICAL HISTORY</b></p>

										<ul style='list-style: none;'>
											<