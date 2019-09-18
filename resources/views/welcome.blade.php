@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Add Report (Mental Helth)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="menPage2">

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
                                <th colspan="2">Environment</th>
                            </tr>
                            <tr>
                                <td>Space offers stimulus and comfort<input type="text" name="question[]" value="Space offers stimulus and comfort" style="display: none;"></td>
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
                                <td>Resources allow safety and independence<input type="text" name="question[]" value="Resources allow safety and independence" style="display: none;"></td>
                                <td>
                                    <select class="form-control" name="answer[]">
                                        <option value="Not seen">Not seen</option>
                                        <option value="Facilitates occupational participation">Facilitates occupational participation</option>
                                        <option value="Allows occupational participation">Allows occupational participation</option>
                                        <option value="Inhibits occupational participation">Inhibits occupational participation</option>
                                        <option value="Restricts occupational participation">Restricts occupational participation</option>
                                    </select>
               