ow->cough }}</b></p>
						<p>Do you produce sputum? <b>{{ $row->sputum }}</b></p>
						<p>If yes, describe.(colour, quantity, thickness, presence of blood stain): <b>{{ $row->describe }}</b></p>
						<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: {{ $row->physio }}</span>
						<p class="text-right">Other related information: <b>{{$row->info}}</b></p>

						<div style="text-align: center;">MEDICATIONS</div>
						<p>Please list all of  the medications[with specific NAME, DOSAGE , FREQUENCY and ROUTE(i.e : by mouth)] that you are currently taking [including over –the-counter, prescription, herbals and vitamins/mineral(s)]: <b>{{ $row->medications }}</b></p>

						@endforeach

					</div>
					<div style="height: 50px; border: 1px solid #000;"></div>

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
						<p>&nbsp;</p>
					</div>
					<div class="container">
						<h3 class="text-left">COMMUNICATION HISTORY</h3>



						@foreach($data7 as $row)

						<p>Please describe any communication difficulties: <b>{{ $row->describe }}</b></p>		<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: {{ $row->physio }}</span>
						<p class="text-right">Other related information: <b>{{ $row->related }}</b></p>

						@endforeach

					</div>
					<div style="height: 50px; border: 1px solid #000;"></div>

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
						<p>&nbsp;</p>
					</div>
					<div class="container">
						<h3 style="text-align: left;">SOCIAL HISTORY/INTEREST/ LIVING ENVIRONMENT</h3>

						@foreach($data8 as $row)

						<p>Spouse name: <b>{{ $row->spouse }}</b><span style='margin-left: 100px;'>age <b>{{ $row->age }}</b></span><span style='margin-left: 100px;'>occupation <b>{{ $row->occupation }}</b></span></p>
						<p>Patients occupation: <b>{{ $row->p_occupation }}</b>
							<p>Type of apartment: <b>{{ $row->apartment }}</b>
								<p>Do you smoke?: <b>{{ $row->smoke }}</b>
									<p>Do you drink?: <b>{{ $row->drink }}</b>
										<i>I have reviewed the information provided and information is complete</i><br>
										<span class="text-right">Therapist name: {{ $row->physio }}</span>
										<p class="text-right">Other related information: <b>{{ $row->info }}</b></p>

										@endforeach
									</div>
									<div style="height: 50px; border: 1px solid #000;"></div>

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
									