								<td>{{ $row->cAROM }}</td>
														<td>{{ $row->cPROM }}</td>
														<td>{{ $row->cend }}</td>
														<td>{{ $row->tAROM }}</td>
														<td>{{ $row->tPROM }}</td>
														<td>{{ $row->tend }}</td>
													</tr>
													@endforeach

													<tr>
														<td colspan='6'>Comment: {{ $row->comment }} </td>
													</tr>                  
												</table>
											</div>
											<div style="border: 1px solid #000; padding: 5px;">
												<table class="table" border="1">
													<p><b><b></p>

														@foreach($data7 as $row)
														<tr>
															<td>Scapular mobility: {{ $row->mobility }}</td>
															<td>Neurological: {{ $row->neuro }}</td>
															<td>Paresthesias: {{ $row->parenthesis }}</td>
														</tr>
														<tr>
															<td>Sensation: {{ $row->sensation }}</td>
															<td>Proprioception: {{ $row->prop }}</td>
															<td>Tone: {{ $row->tone }}</td>
														</tr>
														<tr>
															<td>Reflexes: {{ $row->reflex }}</td>
															<td>Other: {{ $row->other }}</td>
															<td></td>
														</tr>
														@endforeach

													</table>
												</div>
												<div style="border: 1px solid #000; padding: 5px;">
													<p><b>POSTURE</b></p>

													<ul style='list-style: none;'>

														@foreach($data7b as $row)
														<li>{{ $row->answer }}</li>
														@endforeach

													</ul>

												</div>
												<div style="border: 1px solid #000; padding: 5px;">
													<table class="table" border="1">
														<p><b><b></p>

															@foreach($data8 as $row)

															@endforeach

															<tr>
																<td>Muscle Circumference: {{ $row->circumference }}</td>
																<td>Endurance: {{ $row->endurance }}</td>
															</tr>

														</table>
													</div>
													<div style="border: 1px solid #000; padding: 5px;">
														<table class="table" border="1">
															<p><b>special test<b></p>

																@foreach($data8 as $row)
																<tr>
																	<td>{{ $row->test }}</td>
																	<td>{{ $row->answer }}</td>
																</tr>
																@endforeach

															</table>
														</div>
														<div style="border: 1px solid #000; padding: 5px;">
															<table class="table" border="1">
																<p><b><b></p>

																	@foreach($data9 as $row)
																	<tr>
																		<td>Joint Mobility: {{$row->mobility}}</td>
																		<td>Palpation: {{ $row->palpation }}</td>
																		<td>Functional Asseesment: {{ $row->functional }}</td>
																	</tr>
																	<tr>
																		<td>Treatment provided during this visit: {{ $row->treatment }}</td>
																		<td>Response to treatment: {{ $row->response }}</td>
																		
																	</tr>
																	<tr>
																		<td>Rehab Potential/Prognosis: {{ $row->rehab }}</td>
																		<td>Short Term Goals: {{ $row->short_goal }}</td>
																		<td>Long Term Goals: {{ $row->long_goal }}</td>
																	</tr>
																	<tr>
																		<td>Frequency: {{ $row->frequency }}</td>
																		<td>Duration: {{ $row->duration }}</td>
																		<td>Modalities: {{ $row->modalities }}</td>
																	</tr>
																	@endforeach

																</table>
															</div>
															<div style="border: 1px solid #000; padding: 5px;">
																<p><b>POSTURE</b></p>

																<ul style='list-style: none;'>
																	@foreach($data9b as $row)
																	<li>{{ $row->posture }}</li>
																	@endforeach
																</ul>

															</div>
															<div style="border: 1px solid #000; padding: 5px;">
																<table class="table" border="1">
																	<p><b>Mobility<b></p>

																		@foreach($data1