@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Growth and Development</div>
			<div class="panel-body">

				<form method="post" action="{{ url('/paePage5') }}">
					{{ csrf_field() }}

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif

					
					<div class="form-group">
						<label for="exampleInputEmail1">At what age did your child?</label>
						<!--input type="text" name="reason" required  class="form-control" id="exampleInputEmail1" /-->
					</div>
					<table class="table">
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Roll over from stomach to back" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Roll over from back to stomach" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="sit independently" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="crawl" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="walk holding unto furniture" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="walk independently" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="speak first word" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="speak in two words sentence" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Drink from cup" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="use a spoon" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Dress independently" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here">
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="question[]" class="form-control" value="Toilet train" readonly>
							</td>
							<td>
								<input type="text" name="answer[]" required class="form-control" placeholder="input answer here