@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-11">
			<div class="panel-heading"></div>
			<div class="panel-body">

				
				@foreach($data as $row)

				<p class="text-center"><b>INITIAL EVALUATION SUBJECTIVE HISTORY WORKSHEET</b></p>
				<p>Patient Name: {{ $row->name }} <span style="margin-left: 100px;"> Date of Birth: {{ $row->dob }} </span> <span style="margin-left: 100px;"> Date of Eval: {{ $row->created_at }} </span></p>

				@endforeach

				<!--SUBJECTIVE STARTS FROM HERE-->
				@foreach($data2 as $row)
				<div class="box">
					<p class="text-center">SUBJECTIVE</p>
					<p>
						Why are seeking treatment for your child?: <b>{{ $row->reason }}</b><br>
						<span>Has your child has any prior treatment and/in diagnostic testing for this condition? <b>{{ $row->testing }}</b></span><br