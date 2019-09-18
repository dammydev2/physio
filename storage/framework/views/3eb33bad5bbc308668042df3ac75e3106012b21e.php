__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<p>List of all medication currently being taken over-the-counter, prescription, herbals and vitamins: <b> <?php echo e($row->medication); ?> </b></p>
							<p>Please describe any communication difficulties: <b><?php echo e($row->communication); ?></b></p>
							<p>When was the problem first noticed: <b><?php echo e($row->date_notices); ?></b></p>
							<p>Other language(s) besides english spoken at home: <b><?php echo e($row->lang); ?></b></p>
							<p>I have reviewed the information provided and found it to be complete</p>
							<span>Other related information: <b><?php echo e($row->info); ?></b></span><br>
							<p style="text-align: right; margin-right: 20px;"><span>Physiotherapist <b><?php echo e($row->physio_name); ?></b></span></p>

							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
						<!--SOCIAL HISTORY/INTEREST/LIVING ENVIRONMENT-->
						<div class="box">
							<p class="text-center">SOCIAL HISTORY/INTEREST/LIVING ENVIRONMENT</p>

							<?php $__currentLoopData = $data9; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

							<table class="table table-bordered">
								<tr>
									<th>Fathers' name: <b>