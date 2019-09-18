@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading"></div>
			<div class="panel-body">

				

				<style type="text/css">
					.layout{
						width: 700px;
						border: 1px solid #000;
						margin: 0px auto;
					}

					.box{
						border: 1px solid #000;
						padding: 5px;
					}
					@media print {
						#printPageButton {
							display: none;
						}
					}
				</style>
				<button onclick="window.print();" id="printPageButton">Print</button>
				<a href="start.php" id="printPageButton"></a>
				<div class="layout">
					<div class="box">
						<h2 style="text-align: center;">ASSESSMENT PROTOCOL</h2>

						@foreach($data as $row)

						<table class="table">
							<tr>
								<td>Patientame: <b> {{ $row->name }} </b></td>
								<td> Address: <b> {{ $row->address }} </b></td>
							</tr>
							<tr>
								<td>NHIS No: <b> {{ $row->nhis_no }} </b></td> 
								<td>Hosp no: <b> {{ $row->hosp_no }} </b></td>
							</tr>
							<tr>
								<td>Date of Birth: <b> {{$row->dob}} </b></td>
								<td> Gender: <b> {{ $row->gender }} </b></td>
							</tr>
							<tr>
								<td>Tel: <b> {{ $row->phone }} </b></td> 
								<td>Admission date: <b> {{ $row->admission }} </b></td>
							</tr>
							<tr>
								<td>Consent: <b> {{ $row->consent }} </b></td>
								<td>Staff Signature: <b> {{ $row->signature }} </b><td>
								</tr>
								<tr>
									<td>Date: <b> {{ $row->date }} </b></td> 
									<td>Time: <b> {{ $row->time }} </b></td>
								</tr>
								<tr>
									<td>Staffname: <b> {{ $row->print }} </b></td> 
									<td> Designation: <b> {{ $row->designation }} </b></td>
								</tr>
							</table>

							@endforeach

						</div>
						<div class="box">
							<h2 style="text-align: center;">DIAGNOSIS / REASON FOR REFERRAL</h2>

							<table class="table">

								@foreach($data2 as $row)
								<tr>
									<td>HCP: <b> {{ $row->hcp }} </b></td>
									<td> DH: <b> {{ $row->dh }} </b></td>
								</tr>
								<tr>
									<td>PMH: <b> {{ $row->pmh }} </b></td>
									<td> SH: <b> {{ $row->sh }} </td></p>
									</tr>
									<tr>
										<td>Habit: <b> {{ $row->habit }} </b></td>
										<td>  Accomodation: <b> {{ $row->accomodation }} </b></td>
									</tr>
									<tr>
										<td>Did your house has stairs: <b> {{ $row->stairs }} </b></td>
										<td> Did your house has handrails: <b> {{ $row->handrails }} </b></td>
									</tr>
									<tr>
										<td>House type: <b> {{ $row->wc }} </b></td>
										<td> No of children: <b>{{ $row->child }}</b></td>
									</tr>
									<tr>
										<td>No of wives: <b> {{ $row->wives }} </b></td>
										<td> No of pregnancy: <b> {{ $row->pregnancy }} </b></td>
									</tr>
									<tr>
										<td>Aids type: <b> {{ $row->aids }} </b></td>
										<td> Mobility type: <b>sticks 1/2 </b></td>
									</tr>
									<tr>
										<td>Other related Information: <b> {{ $row->info }} </b></td>
									</tr>

									@endforeach
								</table>

							</div>
							<div class="box">
								<h2 style="text-align: center;">OBJECTIVE ASSESSMENT</h2>

								@foreach($data3 as $row)

								<p>Level of Alertness: <b> {{ $row->alert }} </b> </p>
								<p>Respiratory function: <b> {{ $row->resp }} </b> </p>
								<table border='1' width='80%'>
									<tr>
										<td>Cognition: {{ $row->cognition }} : {{ $row->cognition2 }}</td>
										<td>Neglect: {{ $row->neglect }} </td>
										<td>Communication: {{ $row->comm }} </td>
									</tr>
									<tr>
										<td>Swallow: {{ $row->swalow }} </td>
										<td>Pain: {{ $row->pain }} </td>
										<td>Bed mobility:  {{ $row->bed }} </td>
									</tr>
								</table>
								@endforeach

								<table border='1' width='50%'>

									@foreach($data3C as $row)
									<tr>
										<td> {{ $row->question }} </td>
										<td> {{ $row->answer }} </td>
									</tr>
									@endforeach

								</table>
								<p>&nbsp;<