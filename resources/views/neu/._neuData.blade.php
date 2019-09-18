@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Performance Skills</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="occPage6">

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
              <th colspan="3">Performance skills</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th>Comment</th>
            </tr>
            <tr>
              <td colspan="3">Posture</td>
            </tr>
            <!--::::::::Functional Cognition:::::::::-->
            <tr>
              <td style="width: 200px;">Sit<input type="text" name="issue[]" value="Sit" style="display: none;"><input type="text" name="tp[]" value="Posture" style="display: none;"></td>
              <td>
                <select name="answer[]" class="form-control">
                  <option value="impaired">impaired</option>
                  <option value="not impaired">not impaired</option>
                </select>
              </td>
              <td>
                <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
              </td>
            </tr>
            <tr>
              <td style="width: 200px;">Stand<input type="text" name="issue[]" value="Stand" style="display: none;"><input type="text" name="tp[]" value="Posture" style="display: none;"></td>
              <td>
                <select name="answer[]" class="form-control">
                  <option value="impaired">impaired</option>
                  <option value="not impaired">not impaired</option>
                </select>
              </td>
              <td>
                <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
              </td>
            </tr>
            <!--::::::::BALANCE static:::::::::-->
            <tr>
              <td colspan="3">Balance static</td>
            </tr>
            <tr>
              <td style="width: 200px;">Sit<input type="text" name="issue[]" value="Sit" style="display: none;"><input type="text" name="tp[]" value="Balance static" style="display: none;"></td>
              <td>
                <select name="answer[]" class="form-control">
                  <option value="impaired">impaired</option>
                  <option value="not impaired">not impaired</option>
                </select>
              </td>
              <td>
                <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
              </td>
            </tr>
            <tr>
              <td style="width: 200px;">Stand<input type="text" name="issue[]" value="Stand" style="display: none;"><input type="text" name="tp[]" value="Balance static" style="display: none;"></td>
              <td>
                <select name="answer[]" class="form-control">
                  <option value="impaired">impaired</option>
                  <option value="not impaired">not impaired</option>
                </select>
              </td>
              <td>
                <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
              </td>
            </tr>
            <!--::::::::BALANCE dynamic:::::::::-->
            <tr>
              <td colspan="3">Balance dynamic</td>
            </tr>
            <tr>
              <td style="width: 200px;">Sit<input type="text" name="issue[]" value="Sit" style="display: none;"><input type="text" name="tp[]" value="Balance dynamic" style="display: none;"></td>
              <td>
                <select name="answer[]" 