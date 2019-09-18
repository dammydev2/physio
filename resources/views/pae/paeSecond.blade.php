<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading"></div>
			<div class="panel-body">

				

				<style type="text/css">
					.layout{
						width: 700px;
						border: 1px solid #000;
						margin: 0px auto;
					}

					.box{
						border: 1px solid #000;
						padding: 5px;
					}
					@media  print {
						#printPageButton {
							display: none;
						}
					}
				</style>
				<button onclick="window.print();" id="printPageButton">Print</button>
				<a href="start.php" id="printPageButton"></a>
				<div class="layout">
					<div class="box">
						<h2 style="text-align: center;">ASSESSMENT PROTOCOL</h2>

						<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

						<table class="table">
							<tr>
								<td>Patientame: <b> <?php echo e($row->name); ?> </b></td>
								<td> Address: <b> <?php echo e($row->address); ?> </b></td>
							</tr>
							<tr>
								<td>NHIS No: <b> <?php echo e($row->nhis_no); ?> </b></td> 
								<td>Hosp no: <b> <?php echo e($row->hosp_no); ?> </b></td>
							</tr>
							<tr>
								<td>Date of Birth: <b> <?php echo e($row->dob); ?> </b></td>
								<td> Gender: <b> <?php echo e($row->gender); ?> </b></td>
							</tr>
							<tr>
								<td>Tel: <b> <?php echo e($row->phone); ?> </b></td> 
								<td>Admission date: <b> <?php echo e($row->admission); ?> </b></td>
							</tr>
							<tr>
								<td>Consent: <b> <?php echo e($row->consent); ?> </b></td>
								<td>Staff Signature: <b> <?php echo e($row->signature); ?> </b><td>
								</tr>
								<tr>
									<td>Date: <b> <?php echo e($row->date); ?> </b></td> 
									<td>Time: <b> <?php echo e($row->time); ?> </b></td>
								</tr>
								<tr>
									<td>Staffname: <b> <?php echo e($row->print); ?> </b></td> 
									<td> Designation: <b> <?php echo e($row->designation); ?> </b></td>
								</tr>
							</table>

							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						</div>
						<div class="box">
							<h2 style="text-align: center;">DIAGNOSIS / REASON FOR REFERRAL</h2>

							<table class="table">

								<?php $__currentLoopData = $data2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<t