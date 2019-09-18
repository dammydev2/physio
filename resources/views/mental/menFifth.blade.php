@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading">Functional Cognition</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="occPage5">

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
                    <th colspan="3">Functional Cognition</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th></th>
                    <th>Comment</th>
                  </tr>
                  <tr>
                    <!--::::::::Functional Cognition:::::::::-->
                    <td style="width: 200px;">    (a) Sensory/Perceptual skill (position in space, body awareness, midline, neglect etc)<input type="text" name="issue[]" value="(a) Sensory/Perceptual skill (position in space, body awareness, midline, neglect etc)" style="display: none;"><input type="text" name="tp[]" value="Functional Cognition" style="display: none;"></td>
                    <td>
                      <select name="answer[]" class="form-control">
                        <option value="impared">impared</option>
                        <option value="not impared">not impared</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td> (b) Emotional regulation (behavior, coping, etc)r<input type="text" name="issue[]" value="(b) Emotional regulation (behavior, coping, etc)" style="display: none;"><input type="text" name="tp[]" value="Functional Cognition" style="display: none;"></td>
                    <td>
                      <select name="answer[]" class="form-control">
                        <option value="impared">impared</option>
                        <option value="not impared">not impared</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="3">Visual motor/perception</th>
                  </tr>
                  <tr>
                    <td>Visual motor/perception<input type="text" name="issue[]" value="Visual motor/perception" style="display: none;"><input type="text" name="tp[]" value="Visual motor/perception" style="display: none;"><input type="text" name="tp[]" value="Memory" style="display: none;"></td>
                    <td>
                      <select name="answer[]" class="form-control">
                        <option value="impared">impared</option>
                        <option value="not impared">not impared</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="3">Memory</th>
                  </tr>
                  <tr>
                    <td>Short term Memory<input type="text" name="issue[]" value="Short term Memory" style="display: none;"><input type="text" name="tp[]" value="Visual motor/perception" style="display: none;"><input type="text" name="tp[]" value="Memory" style="display: none;"></td>
                    <td>
                      <select name="answer[]" class="form-control">
                        <option value="impared">impared</option>
                        <option value="not impared">not impared</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td>Long term Memory<input type="text" name="issue[]" value="Long term Memory" style="display: none;"></td><input type="text" name="tp[]" value="Memory" style="display: none;">
                    <td>
                      <select name="answer[]" class="form-control">
                        <option value="impared">impared</option>
                        <option value="not impared">not impared</option>
                      </select>
                    </td>
                    <td>
                      <textarea name="Comment[]" required="" class="form-control" placeholder="Enter comment here"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="3">Attention</th>
                  </tr>
                  <tr>
                    <td>Attention<input type="text" name="issue[]" value="Attention" style="display: none;"><input type="tex