@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Activities of Daily living(ADL Status)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="occPage2">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					<table class="table" border="1">
						<tr>
							<th>issue</th>
							<th></th>
							<th>Comment</th>
						</tr>
						<tr>
							<td>Self-feeding<input type="text" name="issue[]" value="Self-feeding" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							<td>Hygiene/grooming<input type="text" name="issue[]" value="Hygiene/grooming" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							<td>Upper body(UB) bathing<input type="text" name="issue[]" value="Upper body(UB) bathing" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							<td>Upper Body dressing<input type="text" name="issue[]" value="Upper Body dressing" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							<td>Bathing/shower<input type="text" name="issue[]" value="Bathing/shower" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							<td>Toilet transfer<input type="text" name="issue[]" value="Toilet transfer" style="display: none;"></td>
							<td>
								<select name="answer[]" class="form-control">
									<option value="Dependent">Dependent</option>
									<option value="DeMax Asst">Max Asst</option>
									<option value="Mod Asst">Mod Asst</option>
									<option value="Min Asst">Min Asst</option>
									<option value="Supervision">Supervision</option>
									<option value="Modified independent">modified independent</option>
									<option value="independent">independent</option>
								</select>
							</td>
							<td>
								<textarea name="Comment[]" required class="form-control" placeholder="Enter comment here"></textarea>
							</td>
						</tr>
						<tr>
							