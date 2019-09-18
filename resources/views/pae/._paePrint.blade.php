<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Add Report (Mental Helth)</div>
			<div class="panel-body">

				<?php if(Session::has('error')): ?>
				<div class="alert alert-danger">
					<?php echo e(Session::get('error')); ?>

				</div>
				<?php endif; ?>

				<form method="post" action="menPage2">

					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>
					
					<?php echo e(csrf_field()); ?>


					<table class="table" border="1">
                        <tr>
                            <th colspan="2">Communication and Inter - action Skills</th>
                        </tr>
                        <tr>
                            <td>Uses appropriate non-verbal expression<input type="text" name="question[]" value="Uses appropriate non-verbal expression" style="display: none;"></td>
                            <td>
                                <select class="form-control" name="answer[]">
                                    <option value="Not seen">Not seen</option>
                                    <option value="Facilitates occupational participation">Facilitates occupational participation</option>
                                    <option value="Allows occupational participation">Allows occupational participation</option>
                                    <option value="Inhibits occupational participation">Inhibits occupational participation</option>
                                    <option value="Restricts occupational participation">Restricts occupational participation</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Initiates and sustains appropriate conversation<input type="text" name="question[]" value="Initiates and sustains appropriate conversation" style="display: none;"></td>
                            <td>
                                <select class="form-control" name="answer[]">
                                    <option value="Not seen">Not seen</option>
                                    <option value="Facilitates occupational participation">Facilitates occupational participation</option>
                                    <option value="Allows occupational participation">Allows occupational participation</option>
                                    <option value="Inhibits occupational participation">Inhibits occupational participation</option>
                                    <option value="Restricts occupational participation">Restricts occupational participation</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Uses appropriate vocal expression<input type="text" name="question[]" value="Uses appropriate vocal expression" style="display: none;"></td>
                            <td>
                                <select class="form-control" name="answer[]">
                                    <option value="Not seen">Not seen</option>
                                    <option value="Facilitates occupational participation">Facilitates occupational participation</option>
                                    <option value="Allows occupational participation">Allows occupational participation</option>
                                    <option value="Inhibits occupational participation">Inhibits occupational participation</option>
                                    <option value="Restricts occupational participation">Restricts occupational participation</option