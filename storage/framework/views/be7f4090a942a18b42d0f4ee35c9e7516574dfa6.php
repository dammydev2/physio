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

											<ul style='list-style: none;'>
												<?php $__currentLoopData = $data3bz; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($row->answer); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>INTENSITY PAIN SCALE<b></p>
												<table class="table" border="1">
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3"><center>Cervical</center></th>
														<th colspan="3"><center>Thoracic</center></th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>

													<?php $__currentLoopData = $data4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e($row->issue); ?></td>
														<td><?php echo e($row->cAROM); ?></td>
														<td><?php echo e($row->cPROM); ?></td>
														<td><?php echo e($row->cend); ?></td>
														<td><?php echo e($row->tAROM); ?></td>
														<td><?php echo e($row->tPROM); ?></td>
														<td><?php echo e($row->tend); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													<tr>
														<td colspan="7">Comment: <?php echo e($row->comment); ?></td>
													</tr>
													
												</table>
											</div>
											<div style="border: 1px solid #000; padding: 5px;">
												<table class="table" border="1">
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3">L Upper extremity</th>
														<th colspan="3">R Upper Extremity</th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>

													<?php $__currentLoopData = $data5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e($row->issue); ?></td>
														<td><?php echo e($row->cAROM); ?></td>
														<td><?php echo e($row->cPROM); ?></td>
														<td><?php echo e($row->cend); ?></td>
														<td><?php echo e($row->tAROM); ?></td>
														<td><?php echo e($row->tPROM); ?></td>
														<td><?php echo e($row->tend); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													
													<tr>
														<td colspan='6'>Comment: <?php echo e($row->comment); ?> </td>
													</tr>                  
												</table>
											</div>
											<div style="border: 1px solid #000; padding: 5px;">
												<table class="table" border="1">
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3">L Lower extremity</th>
														<th colspan="3">R Lower Extremity</th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>
													
													<?php $__currentLoopData = $data6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e($row->issue); ?></td>
														<td><?php echo e($row->cAROM); ?></td>
														<td><?php echo e($row->cPROM); ?></td>
														<td><?php echo e($row->cend); ?></td>
														<td><?php echo e($row->tAROM); ?></td>
														<td><?php echo e($row->tPROM); ?></td>
														<td><?php echo e($row->tend); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													<tr>
														<td colspan='6'>Comment: <?php echo e($row->comment); ?> </td>
													</tr>                  
												</table>
											</div>
											<div style="border: 1px solid #000; padding: 5px;">
												<table class="table" border="1">
													<p><b><b></p>

														<?php $__currentLoopData = $data7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<tr>
															<td>Scapular mobility: <?php echo e($row->mobility); ?></td>
															<td>Neurological: <?php echo e($row->neuro); ?></td>
															<td>Paresthesias: <?php echo e($row->parenthesis); ?></td>
														</tr>
														<tr>
															<td>Sensation: <?php echo e($row->sensation); ?></td>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\physio\resources\views/ort/ortPrint.blade.php ENDPATH**/ ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="pane