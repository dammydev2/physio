@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-6">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="ogPage2">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					<div class="form-group">
						<label for="exampleInputEmail1">What are you seeking treatment for?</label>
						<input type="text" name="reason" required class="form-control" id="exampleInputEmail1" />
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>Do you have any of the following conditions?</label>
						Nausea and vomiting: <input type="checkbox" name="conditions[]" value="Nausea and vomiting">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<p class="text-center text-bold"><b>Musculoskeletal problems:</b> </p>
						Low back pain: <input type="checkbox" name="conditions[]" value="Low back pain">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Radiating pain: <input type="checkbox" name="conditions[]" value="Radiating pain">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Numbness or tingling sensations: <input type="checkbox" name="conditions[]" value="Numbness or tingling sensations">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Cramps: <input type="checkbox" name="conditions[]" value="Cramps">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<div class="form-group w3-sand ">
						<h5 class="text-center text-bold">Swellings of extremities</h5>
						<table class="table container">
							<tr>
								<td>upper limb Right: <input type="checkbox" name="limb[]" value="upper limb Right"></td>
								<td>upper limb Left: <input type="checkbox" name="limb[]" value="upper limb Left"></td>
							</tr>
							<tr>
								<td>Lower limb Right: <input type="checkbox" name="limb[]" value="Lower limb Right"></td>
								<td>Lower limb Left: <input type="checkbox" name="limb[]" value="Lower limb Left"></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<label>Frequency of micturition</label>
						<input type="text" name="frequency" class="form-control">
					</div>
					<div class="form-group">
						<h5 class="text-center">Weakness of muscles</h5>
						<table class="table">
							<tr>
								<td>Facial right: <input type="checkbox" name="muscles[]" value="Lower limb right"></td>
								<td>upper limb Right: <input type="checkbox" name="muscles[]" value="upper limb Right"></td>
								<td>Lower limb Right: <input type="checkbox" name="muscles[]" value="lower limb Right"></td>
							</tr>
							<tr>
								<td>Facial left: <input type="checkbox" name="muscles[]" value="Lower limb Left"></td>
								<td>upper limb left: <input type="checkbox" name="muscles[]" value="upper limb left"></td>
								<td>Lower limb left: <input type="checkbox" name="muscles[]" value="lower limb left"></td>
							</tr>
							<tr>
								<td>Headache: <input type="checkbox" name="muscles[]" value="Headache"></td>
								<td>Epigastric pain: <input type="checkbox" name="muscles[]" value="Epigastric pain"></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div class="w3-sand">
						<div class="form-group w3-sand">
							<label>Have you had any prior treatment and/or diagnostic testing for this condition? </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label>Yes<input type="radio" name="prior" value="yes"></label>&nbsp;&nbsp;&nbsp;&nbsp;
							<label>No<input type="radio" name="prior" value="no"></label><br>&nbsp;&nbsp;&nbsp;&nbsp;
							If yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><input type="checkbox" name="treatment[]" value="x-rays">x-rays</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label><input type="checkbox" name="treatment[]" value="MRI">MRI</l