" width="50%">	
						<?php $__currentLoopData = $data5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($row->question); ?></td>
							<td><?php echo e($row->answer); ?></td>
						</tr>			
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</table>

					<p>Please describe your child: </p>

					<?php $__currentLoopData = $data5b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<ul>
						<li><?php echo e($row->describe); ?></li>
					</ul>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					
					<p>I have reviewed the information provided and found it to be complete</p>
					<span>Other related information <b><?php echo e($row->info); ?></b></span><br>
					<p style="text-align: right; margin-right: 20px;"><span>Physiotherapist <b><?php echo e($row->physio_name); ?></b></span></p>
				</div>
				<!--PREVIOUS MEDICAL HISTORY-->
				<div class="box">
					<p class="text-center">PREVIOUS MEDICAL HISTORY</p>	

					<?php $__currentLoopData = $data6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php $health = $row->health ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<p>How would you classify your child's general health? <b><?php echo e($health); ?></b></p>

					<p>Please describe your child: </p>
					<?php $__currentLoopData = $data6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<ul>
						<li><?php echo e($row->describe); ?></li>
					</ul>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					

					<p>Does your child have any of the following, If yes please explain in the space provided</p>

					<table border="1" width="50%">
						<?php $__currentLoopData = $data6b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<th><?php echo e($row->other); ?></th>
							<th><?php echo e($row->comment); ?></th>
							<tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</table>

							<p>I have reviewed the information provided and found it to be complete</p>

							<span>Other medical history that may impact rate of recovery <b> <?php echo e($row->info); ?> </b></span><br>
							<p style="text-align: right; margin-right: 20px;"><span>Physiotherapist <b><?php echo e($row->physio_name); ?></b></span></p>
						</div>

						<!--MEDICAL PRECAUTIONS AND CONTRADICTIONS-->
						<div class="box">
							<p class="text-center">MEDICAL PRECAUTIONS/CONTRADICTIONS</p>
							<table border="1" class="table">	
								<tr>
									<th>Issue</th>
									<th></th>
									<th>comment</th>
								</tr>	

								<?php $__currentLoopData = $data7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td> <?php echo e($row->issue); ?> </td>
									<td> <?php echo e($row->answer); ?> </td>
									<td> <?php echo e($row->comment); ?> </td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</table>

					