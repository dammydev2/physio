@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Add Report (ORTHOPAEDIC)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="ortPage2">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					<div class="panel panel-primary  w3-card w3-sand">
						<header class="w3-container w3-blue">
							<h4>Vital sign</h4>
						</header>
						<div class="panel-body">
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Blood Pressure</label>
								<input type="text" name="BP" required class="form-control" id="exampleInputEmail1" />
							</div>
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Heart Rate</label>
								<input type="text" name="heart"  required class="form-control" id="exampleInputEmail1" />
							</div>
							<div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Respiration</label>
								<input type="text" name="respiration"  required class="form-control" id="exampleInputEmail1" />
							</div>
						</div>
					</div>

					<div class="form-group col-sm-6">
						<label for="exampleInputEmail1">Chief Compliant</label>
						<input type="text" name="compliant"  placeholder="Enter compliant" required class="form-control" id="exampleInputEmail1" />
					</div>

					<div class="form-group col-sm-6">
						<label for="exampleInputEmail1">History of present injury</label>
						<input type="text" name="history" placeholder="History of present injury"  required  class="form-control" id="exampleInputEmail1" />
					</div>

				<div class="form-group col-sm-12" style="background: #fff;">
					<p>&nbsp;</p>
					<label for="exampleInputEmail1"><b>Past Medical History</b></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Cardiac" />cardiac &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="NIDDM/IDDM" />NIDDM/IDDM &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="CVA" />CVA &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Hypertension" />Hypertension &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Cancer" />Cancer &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Osteoporosis" />Osteoporosis &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Respiratory" />Respiratory &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Fractures" />Fractures &nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="medical[]"  value="Falls" />Falls &nbsp;&nbsp;&nbsp;
					<input type="text" name="medical[]"  placeholder="add other medical history" value="*" /> &nbsp;&nbsp;&nbsp;
					<p>&nbsp;</p>
				</div>

					<div class="form-group col-sm-4">
						<label for="exampleInputEmail1">Current Symptoms</label>
						<input type="text" name="symptoms"  placeholder="Current Symptoms" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group col-sm-4">
						<label for="exampleInputEmail1">Onset</label>
						<input type="text" name="onset"  placeholder="Enter onset" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group col-sm-4">
						<label for="exampleInputEmail1">Pain Rating VRS</label>
						<input type="text" name="pain"  placeholder="Enter pain" required class="form-control" id="exampleInputEmail1" />
					</div>

				<div class="col-sm-12">
					<div class="form-group" style="margin-left: 130px;">
						<label for="exampleInputEmail1"><b>Description</b></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="des"  value="Intermittent" required />Inter