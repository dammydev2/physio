</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <input class="form-check-input" type="radio" name="depend" id="exampleRadios1" value="need assistance" checked>
     <label class="form-check-label" for="exampleRadios1">Need assistance</label>
   </div>
 </div>
</div>

 <!--div class="col-lg-6 ">
    <div class="form-group">
        <div class="input-group w3_w3layouts col-lg-12">
            <span class="input-group-addon" id="basic-addon1">MOBILITY INDEX SCORE</span>
            <select name="mobility" class="form-control" placeholder="s" aria-describedby="basic-addon1" required="" >
                <option>CHOOSE A SCORE</option>
                <option value="normal" >0 = Unable to perform</option>
                <option value="independent" >1 = Assistance of two peoples</option>
                <option value="assistance of 1/2" >2 = Assistance of one person </option>
                <option value="immobile" >3 = Requires supervision or verbal instruction</option>
                <option>4 = Requires an aid or appliance </option>
                <option>5 = Independent </option>
            </select>
        </div>
    </div>
  </div-->
  <div class="col-lg-10 ">
    <div class="form-group">
      <div class="input-group w3_w3layouts col-lg-12">
        <label for="exampleFormControlTextarea1">ACTION TAKEN</label>
        <textarea name="action" class="form-control" placeholder="" aria-describedby="basic-addon1" rows="2" required="" / > </textarea>
      </div>
    </div>
  </div>
  <table class="table" border="1">
    <tr>
      <th colspan="2" class="text-center"><h3>THE MODIFIED RIVERMEAD MOBILITY INDEX</h3></th>
    </tr>
    <tr>
      <th>Item</th>
      <th>Score</th>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="turning over" style="display: none;">
        <b>Turning Over</b><br>
        Please turn over from your right to left
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two persons</option>
          <option value="assistance of one people">assistance of one persons</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="lying or siting" style="display: none;">
        <b>lying or siting</b><br>
        Please sit uo on the side of the bed<br>
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="sitting balance" style="display: none;">
        <b>Sitting balance</b><br>
        Please sit on the edge of the bed<br>
        (the assesor time for the patient for 10 seconds)
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="sitting to standing" style="display: none;">
        <b>Sitting to standing</b><br>
        Please stand up from the chair<br>
        (the patient takes less than 15 seconds)
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="standing" style="display: none;">
        <b>standing</b><br>
        Please remain standing<br>
        (the assessor times the patient for 10 seconds)
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="transfer" style="display: none;">
        <b>transfer</b><br>
        Please go from your bed to the chair and back again<br>
        (the assessor places the chair on the patient's unaffected side)
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="Walking indoor" style="display: none;">
        <b>Walking indoor</b><br>
        Please walk for 10 metres in your usual way
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="question[]" value="stairs" style="display: none;">
        <b>Stairs</b><br>
        Please climb up and down the flight of stairs in your usual way
      </td>
      <td>
        <select name="answer[]" class="form-control">
          <option value="Unable to perform">Unable to perform</option>
          <option value="assistance of two people">assistance of two people</option>
          <option value="assistance of one people">assistance of one people</option>
          <option value="requires supervision or verbal instruction">requires supervision or verbal instruction</option>
          <option value="requires an aid or an appliance">requires an aid or an appliance</option>
          <option value="independent">independent</option>
        </select>
      </td>
    </tr>
  </table>

  <input type="submit" name="" value="continue" class="btn btn-primary">

</form>

</div>
</div>


</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Library/WebServer/Documents/physio/resources/views/neu/neuForth.blade.php ENDPATH**/ ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-9">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        <?php if(Session::has('error')): ?>
        <div class="alert alert-danger">
          <?php echo e(Session::get('error')); ?>

        </div>
        <?php endif; ?>

        <form method="post" action="ortPage11">

          <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php echo e(csrf_field()); ?>


          <div class="form-group" style="background: #fff;">
            <p>&nbsp;</p>
            <label for="exampleInputEmail1"><b>Deviations</b></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Decrease heel toe gait" />Decrease heel toe gait &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Decrease reciprocal arm swing" />Decrease reciprocal arm swing &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Decrease base of support" />Decrease base of support &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Loss of balance (LOB)" />Loss of balance (LOB) &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Antalgic gait" />Antalgic gait &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Shuffling gait" />Shuffling gait &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Waddling Cadence (Fast/Slow)" />Waddling Cadence (Fast/Slow) &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Deviations[]"  value="Festinating" />Festinating &nbsp;&nbsp;&nbsp;
            <input type="text" name="Deviations[]" value="*"  placeholder="Enter other here" /> &nbsp;&nbsp;&nbsp;
            <p>&nbsp;</p>
          </div>

          <input type="submit" name="Continue" class="btn btn-primary">

        </form>

      </div>
    </div>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\physio\resources\views/ort/ortEleven.blade.php ENDPATH**/ ?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				<a href="#" onclick="window.print()">print</a>
				<!--<link rel="stylesheet" type="text/css" href="css/w3.css">-->
				<div class="col-md-8 w3-sand" style="border: 2px solid #000; width: 700px; margin: 0px auto">
					<h4 style="text-align: center;">INITIAL EVALUATION SUBJECTIVE HISTORY WORKSHEET</h4>
					<h5 class="text-right">O & G</h5>
					<div class="container">

						<div class="col-md-8">
						<table class="table table-bordered col-sm-8">
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
					<div class="container col-md-12" style="border: 1px solid #000;">
						<h5 style="text-align: center;">SUBJECTIVE</h5>

						<?php $__currentLoopData = $data2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<p>What are you seeking treatment for? <b> <?php echo e($row->reason); ?> </b>
						</p>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						<p>Patient condition include: </p>

						<?php $__currentLoopData = $data2b1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $get): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

						<li class="text-bold" style="list-style: none; margin-left: 150px;"><?php echo e($get->answer); ?></li>

						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						<p>&nbsp;</p>

						<p>Swellings of extremities: </p>
						
						<?php $__currentLoopData = $data2b2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $get): $__env->