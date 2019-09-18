<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Previous Medical History</div>
			<div class="panel-body">

				<form method="post" action="<?php echo e(url('/paePage6')); ?>">
					<?php echo e(csrf_field()); ?>


					<?php if($errors->any()): ?>
					<div class="alert alert-danger">
						<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label for="exampleInputEmail1">How would you classify your child's general health?</label>
						<input type="radio" name="health" required value="Good" id="exampleInputEmail1" /> Good &nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="health" required value="fair" id="exampleInputEmail1" /> fair &nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="health" required value="poor" id="exampleInputEmail1" /> poor &nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<p>&nbsp;</p>
					<p>Please describe your child</p>
					<div class="form-group">
						<label><input type="checkbox" name="describe[]" value="allergies"> allergies</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="encephalitis"> encephalitis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="multiple scierosis"> multiple scierosis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Rheumatic fever"> rheumatic fever</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Asthma / breathing difficulties"> Asthma / breathing difficulties</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Enlarged Glands"> Enlarged Glands</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Nausea/vomiting"> Nausea/vomiting</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Ringing of the ear"> Ringing of the ear</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Bronchitis"> Bronchitis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Fever"> fever</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Night pain"> night pain</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Seizures/Epilepsy"> Seizures/Epilepsy</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="cancer"> cancer</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Head injury"> Head injury tantrums</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Osteoarthritis"> Osteoarthritis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Smoking history"> Smoking History</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Chicken pox"> Chiken pox</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="heart Disease"> Heart disease</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Peacemaker"> Peacemaker</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Stroke/TIA"> Stroke/TIA</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Chronic colds"> Chronic colds</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="High blood Pressure"> High blood pressure</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="Parkinson Disease"> Parkinson disease</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="surgeries"> Surgeries</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="chronic laryngitis"> chronic laryngitis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="hypoglycemia"> hypoglycemia</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="physical abnormalities"> Physical abnormalities</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="thyroid disease"> thyroid disease</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="cogenital defects"> cogenital defects</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="measeles"> measeles</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="pnemonia"> pnemonia</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="tonsilitis"> tonsilitis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="depresion"> depresion</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="meningitis"> meningitis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="polio"> polio</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="tuberculosis"> tuberculosis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="diabetis type I and II"> diabetis (Type I and II)</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="metal implant"> metal implant</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="pregnancy"> Pregnancy (currently)</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="whopping cough"> whooping cough</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="dizziness/fainting"> dizziness/fainting</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="mumps"> mumps</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<label><input type="checkbox" name="describe[]" value="rheumatoid Arthritis"> rheumatoid Arthritis</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" name="describe[]" placeholder="Enter other here">&nbsp;&nbsp;&nbsp;&nbsp;
					</div>

					<div class="form-group">
						<p>&nbsp;</p>
						<p>Does your child have any of the following, If yes please explain in the space provided</p>
						<table class="table" border="1">
							<tr>
								<td><input type="checkbox" name="other[]" value="Earache/Ear defection"> Earache/Ear defection</td>
								<td><input type="text" class="form-control" name="comment[]" placeholder="Describe"></td>
							</tr>
							<tr>
								<td><input type="checkbox" name="other[]" value="Earing difficulties"> Earing difficulties</td>
								<td><input type="text" class="form-control" name="comment[]" placeholder="if yes. Aided?"></td>
							</tr>
							<tr>
								<td><textarea name="other[]" readonly="" class="form-control">have you ever purchased or rented durable Medical Equipment, orthortics, prosthetics or supplies?</textarea></td>
								<td><input type="text" class="form-control" name="comment[]" placeholder="Please explain"></td>
							</tr>
						</table>
					</div>
					<div class="checkbox">
						<label><input type="checkbox" required> I have reviewed the information provided above and I found them to be complete</label>
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Physiotherapist (Name)</label>
						<input type="text" name="physio" value="<?php echo e(\Auth::User()->name); ?>" readonly required class="form-control" id="exampleInputEmail1" />
					</div>
					<p>&nbsp;</p>
					<textarea name="info" placeholder="Please list medical history or co-morbidities that may impact Rate of recovery" class="form-control"></textarea>
					<p>&nbsp;</p>


					<input type="submit" name="submit" class="btn btn-primary">

				</form>

			</div>
		</div>


	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Library/WebServer/Documents/physio/resources/views/pae/paeSixth.blade.php ENDPATH**/ ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                