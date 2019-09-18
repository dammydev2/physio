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
                                <th colspan="2">Process skills</th>
                            </tr>
                            <tr>
                                <td>Chooses/uses equipment appropriately<input type="text" name="question[]" value="Chooses/uses equipment appropriately" style="display: none;"></td>
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
                                <td>Maintains focus throughout task/sequence<input type="text" name="question[]" value="Maintains focus throughout task/sequence" style="display: none;"></td>
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
                                <td>Works in an orderly fashion<input type="text" name="question[]" value="Works in an orderly fashion" style="display: none;"></td>
                                <td>
                                    <select class="form-control" name="answer[]">
                                        <option value="Not seen">Not seen</option>
                                        <option value="Facilitates occupational participation">Facilitates occupational participation</option>
                                        <option value="Allows occupational participation">Allows occupational participation</option>
                                        <option value="Inhibits occupational participation">Inhibits occupational participation</option>
                                        <option value="Restricts occupational participation">Restricts occupational participation</option>
                                    </select>
                             