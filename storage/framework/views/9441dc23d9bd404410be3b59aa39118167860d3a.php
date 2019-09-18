<td>Proprioception: <?php echo e($row->prop); ?></td>
															<td>Tone: <?php echo e($row->tone); ?></td>
														</tr>
														<tr>
															<td>Reflexes: <?php echo e($row->reflex); ?></td>
															<td>Other: <?php echo e($row->other); ?></td>
															<td></td>
														</tr>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													</table>
												</div>
												<div style="border: 1px solid #000; padding: 5px;">
													<p><b>POSTURE</b></p>

													<ul style='list-style: none;'>

														<?php $__currentLoopData = $data7b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<li><?php echo e($row->answer); ?></li>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													</ul>

												</div>
												<div style="border: 1px solid #000; padding: 5px;">
													<table class="table" border="1">
														<p><b><b></p>

															<?php $__currentLoopData = $data8; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

															<tr>
																<td>Muscle Circumference: <?php echo e($row->circumference); ?></td>
																<td>Endurance: <?php echo e($row->endurance); ?></td>
															</tr>

														</table>
													</div>
													<div style="border: 1px solid #000; padding: 5px;">
														<table class="table" border="1">
															<p><b>special test<b></p>

																<?php $__currentLoopData = $data8; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<tr>
																	<td><?php echo e($row->test); ?></td>
																	<td><?php echo e($row->answer); ?></td>
																</tr>
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

															</table>
														</div>
														<div style="border: 1px solid #000; padding: 5px;">
															<table class="table" border="1">
																<p><b><b></p>

																	<?php $__currentLoopData = $data9; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<tr>
																		<td>Joint Mobility: <?php echo e($row->mobility); ?></td>
																		<td>Palpation: <?php echo e($row->palpation); ?></td>
																		<td>Functional Asseesment: <?php echo e($row->functional); ?></td>
																	</tr>
																	<tr>
																		<td>Treatment provided during this visit: <?php echo e($row->treatment); ?></td>
																		<td>Response to treatment: <?php echo e($row->response); ?></td>
																		
																	</tr>
																	<tr>
																		<td>Rehab Potential/Prognosis: <?php echo e($row->rehab); ?></td>
																		<td>Short Term Goals: <?php echo e($row->short_goal); ?></td>
																		<td>Long Term Goals: <?php echo e($row->long_goal); ?></td>
																	</tr>
																	<tr>
																		<td>Frequency: <?php echo e($row->frequency); ?></td>
																		<td>Duration: <?php echo e($row->duration); ?></td>
																		<td>Modalities: <?php echo e($row->modalities); ?></td>
																	</tr>
																	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

																</table>
															</div>
															<div style="border: 1px solid #000; padding: 5px;">
																<p><b>POSTURE</b></p>

																<ul style='list-style: none;'>
																	<?php $__currentLoopData = $data9b; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<li><?php echo e($row->posture); ?></li>
																	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																</ul>

															</div>
															<div style="border: 1px solid #000; padding: 5px;">
																<table class="table" border="1">
																	<p><b>Mobility<b></p>

																		<?php $__currentLoopData = $data10; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																		<tr>
																			<td><?php echo e($chk->test); ?></td>
																			<td><?php echo e($chk->answer); ?></td>
																		</tr>
																		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

																		<tr>
																			<td colspan='2'>Comment: <?php echo e($chk->comment); ?> </td>
																		</tr>                  
																	</table>
																</div>
																<div style="border: 1px solid #000; padding: 5px;">
																	<table class="table" border="1">
																		<p><b>Gait<b></p>
																			<tr>
																				<td>Weight Bearing: <?php echo e($row->weight); ?></td>
																				<td>Assistive Device: <?php echo e($row->assistive); ?></td>
																				<td>Assistance: <?php echo e($row->assistance); ?></td>
																			</tr>
																			<tr>
																				<td>Distance: <?php echo e($row->distance); ?></td>
																				<td>Proprioception: <?php echo e($row->pro); ?></td>
																			</tr>
																		</table>
																	</div>
																	<div style="border: 1px solid #000; padding: 5px;">
																		<p><b>Deviations</b></p>

																		<ul style='list-style: none;'>

																			<?php $__currentLoopData = $data11; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<li><?php echo e($row->deviations); ?></li>
																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

																		</ul>

																	</div>
																	<div style="border: 1px solid #000; padding: 5px;">
																		<table class="table" border="1">
																			<p><b>Special test<b></p>

																				<?php $__currentLoopData = $data12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																				Endurance: <?php echo e($row->endurance); ?>      

																				<?php $__currentLoopData = $data12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																				<tr>
																					<td><?php echo e($row->test); ?></td>
																					<td><?php echo e($row->answer); ?></td>
																				</tr>
																				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

																			</table>
																		</div>
																		
																		<?php $__currentLoopData = $last; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<p>&nbsp;</p>
																			<p>Physiotherapist: <?php echo e($row->physio); ?></p>
																			<p>Date: <?php echo e($row->dt); ?></p>

																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

																		</div>
																	</div>

																</div>
															</div>


														</div>
													</div>
													<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Library/WebServer/Documents/physio/resources/views/ort/ortPrint.blade.php ENDPATH**/ ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         