addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<tr>
													<td>At Worse: <?php echo e($row->worse); ?></td>
													<td>At best: <?php echo e($row->best); ?></td>
													<td>Current: <?php echo e($row->current); ?></td>
												</tr>
												<tr>
													<td>Duration: <?php echo e($row->duration); ?></td>
													<td>Aggravating Factors: <?php echo e($row->aggravating); ?></td>
													<td>Alleviating Factors: <?php echo e($row->alleviating); ?></td>
												</tr>
												<tr>
													<td>24-hour behavior of symptoms: <?php echo e($row->behaviour); ?></td>
													<td>Medication/Allergies: <?php echo e($row->medication); ?></td>
													<td>Home Environment: <?php echo e($row->home); ?></td>
												</tr>
												<tr>
													<td>Tests and Measures: <?php echo e($row->measure); ?></td>
													<td>Joint Clearing: <?php echo e($row->joint); ?></td>
													<td>Flexibility: <?php echo e($row->flexibility); ?></td>
												</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

											</table>
										</div>
										<!--<div style="border: 1px solid #000; padding: 5px;">
											<p><b>PAST MEDICAL HISTORY</b></p>


											<ul style='list-style: none;'>
												<li>Hypertension</li>
											</ul>


											<ul style='list-style: none;'>
												<li></li>
											</ul>
										</div>-->
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>PRECAUTIONS</b></p>

											<ul style='list-style: none;'>
												<?php $__currentLoopData = $data3bx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($row->answer); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>LIVING SITUATION</b></p>

											<ul style='list-style: none;'>
												<?php $__currentLoopData = $data3by; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($row->answer); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>EQUIPMENT AT HOME</b></p>

											<u