a as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<p>Level of Alertness: <b> <?php echo e($row->alert); ?> </b> </p>
								<p>Respiratory function: <b> <?php echo e($row->resp); ?> </b> </p>
								<table border='1' width='80%'>
									<tr>
										<td>Cognition: <?php echo e($row->cognition); ?> : <?php echo e($row->cognition2); ?></td>
										<td>Neglect: <?php echo e($row->neglect); ?> </td>
										<td>Communication: <?php echo e($row->comm); ?> </td>
									</tr>
									<tr>
										<td>Swallow: <?php echo e($row->swalow); ?> </td>
										<td>Pain: <?php echo e($row->pain); ?> </td>
										<td>Bed mobility:  <?php echo e($row->bed); ?> </td>
									</tr>
								</table>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								<table border='1' width='50%'>

									<?php $__currentLoopData = $data3C; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<td> <?php echo e($row->question); ?> </td>
										<td> <?php echo e($row->answer); ?> </td>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</table>
								<p>&nbsp;</p>

								<table border='1' width='70%'>
									<?php $__currentLoopData = $data3b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<td> <?php echo e($row->tp); ?> </td>
										<td> <?php echo e($row->issue); ?> </td>
										<td> <?php echo e($row->answer); ?> </td>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</table>
							</div>
							<div class="box">
								<h2 style="text-align: center;">OBJECT ASSESSMENT</h2>
								<table border='1'>

									<?php $__currentLoopData = $data4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<td>At risk of respiratory complications: <?php echo e($row->respiratory); ?> </td>
										<td>At risk of abdominal muscle tone and contractures: <?php echo e($row->muscle_tone); ?> </td>
										<td>At risk of shoulder pain: <?php echo e($row->shoulder); ?> </td>
									</tr>
									<tr>
										<td>sitting balance: <?php echo e($row->sitting); ?>: Comment <?php echo e($row->sitting2); ?> </td>
										<td>ability to transfer independenty?: <?php echo e($row->depend); ?> </td>
									</tr>

									<p>Action to be taken: <?php echo e($row->action); ?> </p>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

								</table>
								<table border='1'><table border='1' width='60%'>

									<?php $__currentLoopData = $data4b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<th> <?php echo e($row->question); ?> </th>
										<th> <?php echo e($row->answer); ?> </th>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</table>

								<div class="box">
									<h2 style="text-align: center;">SUMMARY OF PROBLEM COMPLICATION</h2>

									<?php $__currentLoopData = $data5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<p>Analysis of Findings: <b> <?php echo e($row->findings); ?> </b></p>
									<p>Physical Impression: <b> <?php echo e($row->impression); ?> </b></p>
									<p>Plan of treatment: <b> <?php echo e($row->treatment); ?> </b></p>
									<p>Intervention/Means of treatment: <b> <?php echo e($row->means); ?> </b></p>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</div>


								<table border='1'><table border='1' width='60%'></table>
								<p>&nbsp