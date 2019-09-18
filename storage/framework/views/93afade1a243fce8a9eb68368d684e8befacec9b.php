>
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
										