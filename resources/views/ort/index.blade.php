Does your child have any of the following, If yes please explain in the space provided</p>

					<table border="1" width="50%">
						@foreach($data6b as $row)
						<tr>
							<th>{{ $row->other }}</th>
							<th>{{ $row->comment }}</th>
							<tr>
								@endforeach
							</table>

							<p>I have reviewed the information provided and found it to be complete</p>

							<span>Other medical history that may impact rate of recovery <b> {{ $row->info }} </b></span><br>
							<p style="text-align: right; margin-right: 20px;"><span>Physiotherapist <b>{{ $row->physio_name }}</b></span></p>
						</div>

						<!--MEDICAL PRECAUTIONS AND CONTRADICTIONS-->
						<div class="box">
							<p class="text-center">MEDICAL PRECAUTIONS/CONTRADICTIONS</p>
							<table border="1" class="table">	
								<tr>
									<th>Issue</th>
									<th></th>
									<th>comment</th>
								<