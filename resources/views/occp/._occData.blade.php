>Gravida & Parity: <b> {{ $row->parity }} </b><br></p>
						<p>Number of living children: Boys <b>{{ $row->boys }}</b> &nbsp;&nbsp;&nbsp;&nbsp; girls <b> {{ $row->girls }} </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; twins <b>{{ $row->twins }}</b><br></p>
						<p>Multiple pregnancies: <b>{{ $row->multiple }}</b><br></p>
						<p>Duration between pregnancies: <b>{{ $row->duration }}</b><br></p>
						<p>Were there any complications during pregnancy?: <b>{{ $row->complication }}</b><br></p>
						<p>Comments: <b>{{ $row->comment }}</b><br></p>
						<p>Was the pregnancy full term?: <b>{{ $row->term }}</b><br></p>
						<p>Comments: <b>{{ $row->term_comment }}</b><br></p>
						<p>Were you on any drugs or medication during pregnancy? <b>{{ $row->drug }}</b><br></p>
						<p>Comments: <b>{{ $row->drug_comment }}</b><br></p>
						<p>Was labour and delivery normal? <b>{{ $row->labour }}</b><br></p>
						<p>Comments: <b>{{ $row->labour_comment }}</b><br></p>
						<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: {{ $physio = $row->physio }}</span>
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
							<tr>
								<td> Date of Eval: <b> {{ $row->dt }} </b></td>
								<td>Address: <b> {{ $row->address }} </b></td>
							</tr>
							@endforeach
						</table>
						</div>

						<p>&nbsp;</p>
					</div>

					@foreach($data5 as $row)
					<div class="container">
						<h5 class="text-center">PREVIOUS MEDICAL HISTORY</h5>
						<p>How would you classify your general health? <b>{{ $row->health }}</b></p>
					</div>
					@endforeach

					<div class="container">
						<i>Patient past medical history: <br></i>

						@foreach($data5b as $get)

						<li class="text-bold" style="list-style: none; margin-left: 150px;">{{ $get->answer }}</li>

						@endforeach

						<p>Is there any other information concerning your medical history that we should know about? <b>{{ $row->information }}</b></p>
						<p>Have you ever purchased or rented Durable Medical Equipments, Orthotocs, Prosthetics. Or Supplies? <b>{{ $row->durable }}</b></p>
						<p>Explanation on durable medical equipment <b>{{ $row->explain }}</b></p>
						<i>I have reviewed the information provided and information is complete</i><br>
						<span class="text-right">Therapist name: {{ $physio }}</span>
						<p class="text-right">List Previous Medical History or Co-Morbidities that May Impact Rate of Recovery: <b>{{ $row->info }}</b></p>
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

						@foreach($data6 as $row)

						<p>Do you have any movement restriction? <b>{{ $row->moverment }}</b></p>
						<p>If yes , please list? <b>{{ $row->list }}</b></p>
						<p>Do you have any difficulty in breathing? <b>{{ $row->breathing }}</b></p>
						<p>Do you cough? <b>{{ $r