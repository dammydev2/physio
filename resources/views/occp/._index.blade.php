@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				<a href="#" onclick="window.print()">print</a>
				<!--<link rel="stylesheet" type="text/css" href="css/w3.css">-->
				<div class="col-md-8 w3-sand" style="border: 2px solid #000; width: 700px; margin: 0px auto">
					<h4 style="text-align: center;">INITIAL EVALUATION SUBJECTIVE HISTORY WORKSHEET</h4>
					<h5 class="text-right">O & G</h5>
					<div class="container">

						<div class="col-md-8">
						<table class="table table-bordered col-sm-8">
							@foreach($data as $row)
							<tr>
								<td>Patient Name: <b> {{ $row->name }} </b></td>
								<td> Date of Birth: <b> {{ $row->dob }} </b></td>
							</tr>
							<tr>
								<td>Marital Status: <b> {{ $row->marital }} </b></td>
								<td> Duration of Marriage: <b> {{ $row->duration }} </b></td>
							</tr>
							<tr>
								<td> Date of Eval: <b> {{ $row->dt }} </b></td>
								<td>Address: <b> {{ $row->address }} </b></td>
							</tr>
							@endforeach
						</table>
						</div>

						<p>&nbsp;</p>
					</div>
					<div class="container col-md-12" style="border: 1px solid #000;">
						<h5 style="text-align: center;">SUBJECTIVE</h5>

						@foreach($data2 as $row)
						<p>What are you seeking treatment for? <b> {{ $row->reason }} </b>
						</p>
						@endforeach

						<p>Patient condition include: </p>

						@foreach($data2b1 as $get)

						<li class="text-bold" style="list-style: none; margin-left: 150px;">{{ $get->answer }}</li>

						@endforeach

						<p>&nbsp;</p>

						<p>Swellings of extremities: </p>
						
						@foreach($data2b2 as $get)

						<li class="text-bold" style="list-style: none; margin-left: 150px;">{{ $get->answer }}</li>

						@endforeach


						<p>Frequency of micturition: <b> {{ $row->frequency }} </b></p>

						@foreach($data2b3 as $get)

						<li class="text-bold" style="list-style: none; margin-left: 150px;">{{ $get->answer }}</li>

						@endforeach

						<p>Have you had any prior treatment and/or diagnostic testing for this condition?: <b>{{ $row->prior }}</b></p>
						If yes: <b style='margin-left: 20px;'>{{ $row->explain }}</b><br>

						<p>Date of next Doctor’s appointment: <b>{{ $row->dt }}</b></p>
						<i>I have reviewed the information provided and information is complete</i>
						<p>Subjective history: <b>{{ $row->info }}</b></p>
						<span class="text-right">Therapist name: {{ $row->physio }}</span>
					</div>

					<p>&nbsp;</p>

					@foreach($data2b5 as $row2)
					<div>
						<h3 style="text-align: center;">CURRENT COMPLAINTS</h3>
						<p>What are your main concerns regarding your condition: <b>{{ $row2->answer }}</b><br></p>		<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: {{ $row->physio }}</span>
					</div>
					@endforeach


					<!--<div>
						<h3 style="text-align: center;">OBSTETRICS HISTORY</h3>
						If yes:		<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: Yakubu Damilola opeyemi</span>
						<p class="text-right">Other related information: <b></b></p>
					</div>-->

					<div class="container">

<div class="col-sm-8">
						<table class="table table-bordered">
							@foreach($data as $row)
							<tr>
								<td>Patient Name: <b> {{ $row->name }} </b></td>
								<td> Date of Birth: <b> {{ $row->dob }} </b></td>
							</tr>
							<tr>
								<td>Marital Status: <b> {{ $row->marital }} </b></td>
								<td> Duration of Marriage: <b> {{ $row->duration }} </b></td>
							</tr>
							<tr>
								<td> Date of Eval: <b> {{ $row->dt }} </b></td>
								<td>Address: <b> {{ $row->address }} </b></td>
							</tr>
							@endforeach
						</table>
						</div>

					</div>
					<div>
						@foreach($data4 as $row)
						<h3 style="text-align: center;">OBSTETRICS HISTORY</h3>
						<p