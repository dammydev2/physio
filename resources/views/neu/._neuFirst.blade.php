@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Instrumental ADL Status</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="occPage3">

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
                                  <td>Kitchen survival skills<input type="text" name="issue[]" value="Kitchen survival skills" style="display: none;"></td>
                                  <td>
                                      <select name="answer[]" class="form-control">
                                          <option value="Dependent">Dependent</option>
                                          <option value="DeMax Asst">Max Asst</option>
                                          <option value="Mod Asst">Mod Asst</option>
                                          <option value="Min Asst">Min Asst</option>
                                          <option value="Supervision">Supervision</option>
                                          <option value="independent">independent</option>
                                          <option value="not applicable">not applicable</option>
                                      </select>
                                  </td>
                                  <td>
                                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                                  </td>
                              </tr>
                              <tr>
                                  <td>Meal preparation<input type="text" name="issue[]" value="Meal preparation" style="display: none;"></td>
                                  <td>
                                      <select name="answer[]" class="form-control">
                                          <option value="Dependent">Dependent</option>
                                          <option value="DeMax Asst">Max Asst</option>
                                          <option value="Mod Asst">Mod Asst</option>
                                          <option value="Min Asst">Min Asst</option>
                                          <option value="Supervision">Supervision</option>
                                          <option value="independent">independent</option>
                                          <option value="not applicable">not applicable</option>
                                      </select>
                                  </td>
                                  <td>
                                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                                  </td>
                              </tr>
                              <tr>
                                  <td>Shopping<input type="text" name="issue[]" value="Shopping" style="display: none;"></td>
                                  <td>
                                      <select name="answer[]" class="form-control">
                                          <option value="Dependent">Dependent</option>
                                          <option value="DeMax Asst">Max Asst</option>
                                          <option value="Mod Asst">Mod Asst</option>
                                          <option value="Min Asst">Min Asst</option>
                                          <option value="Supervisi