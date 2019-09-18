yle: none;'>
												<li>Hypertension</li>
											</ul>


											<ul style='list-style: none;'>
												<li></li>
											</ul>
										</div>-->
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>PRECAUTIONS</b></p>

											<ul style='list-style: none;'>
												@foreach($data3bx as $row)
												<li>{{ $row->answer }}</li>
												@endforeach
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>LIVING SITUATION</b></p>

											<ul style='list-style: none;'>
												@foreach($data3by as $row)
												<li>{{ $row->answer }}</li>
												@endforeach
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>EQUIPMENT AT HOME</b></p>

											<ul style='list-style: none;'>
												@foreach($data3bz as $row)
												<li>{{ $row->answer }}</li>
												@endforeach
											</ul>

										</div>
										<div style="border: 1px solid #000; padding: 5px;">
											<p><b>INTENSITY PAIN SCALE<b></p>
												<table class="table" border="1">
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3"><center>Cervical</center></th>
														<th colspan="3"><center>Thoracic</center></th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>

													@foreach($data4 as $row)
													<tr>
														<td>{{ $row->issue }}</td>
														<td>{{ $row->cAROM }}</td>
														<td>{{ $row->cPROM }}</td>
														<td>{{ $row->cend }}</td>
														<td>{{ $row->tAROM }}</td>
														<td>{{ $row->tPROM }}</td>
														<td>{{ $row->tend }}</td>
													</tr>
													@endforeach

													<tr>
														<td colspan="7">Comment: {{ $row->comment }}</td>
													</tr>
													
												</table>
											</div>
											<div style="border: 1px solid #000; padding: 5px;">
												<table class="table" border="1">
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3">L Upper extremity</th>
														<th colspan="3">R Upper Extremity</th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>

													@foreach($data5 as $row)
													<tr>
														<td>{{ $row->issue }}</td>
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
													<tr>
														<td colspan="7">RANGE OF MOTION</td>
													</tr>
													<tr>
														<th></th>
														<th colspan="3">L Lower extremity</th>
														<th colspan="3">R Lower Extremity</th>
													</tr>
													<tr>
														<th></th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
														<th>AROM</th>
														<th>PROM</th>
														<th>END FEEL</th>
													</tr>
													
													@foreach($data6 as $row)
													<tr>
														<td>{{ $row->issue }}</td>
						