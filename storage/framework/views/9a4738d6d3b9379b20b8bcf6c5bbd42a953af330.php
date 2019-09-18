 [including over –the-counter, prescription, herbals and vitamins/mineral(s)]: <b><?php echo e($row->medications); ?></b></p>

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

					</div>
					<div style="height: 50px; border: 1px solid #000;"></div>

					<div class="container">
						<div class="col-sm-8">
						<table class="table table-bordered">
							<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td>Patient Name: <b> <?php echo e($row->name); ?> </b></td>
								<td> Date of Birth: <b> <?php echo e($row->dob); ?> </b></td>
							</tr>
							<tr>
								<td>Marital Status: <b> <?php echo e($row->marital); ?> </b></td>
								<td> Duration of Marriage: <b> <?php echo e($row->duration); ?> </b></td>
							</tr>
							<tr>
								<td> Date of Eval: <b> <?php echo e($row->dt); ?> </b></td>
								<td>Address: <b> <?php echo e($row->address); ?> </b></td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</table>
						</div>
						<p>&nbsp;</p>
					</div>
					<div class="container">
						<h3 class="text-left">COMMUNICATION HISTORY</h3>



						<?php $__currentLoopData = $data7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->in