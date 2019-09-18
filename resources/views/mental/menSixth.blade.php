@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-10">
			<div class="panel-heading" id="printPageButton">PRINT OCCUPATIONAL THERAPHY</div>
			<div class="panel-body">

				
        <!-- Page Content Holder -->
        <div id="content w3-light-grey">
          <!-- top-bar -->
          <button onclick="window.print()" id="printPageButton">Print</button>
          <div style="background: #fff; margin-left: 30px; width: 700px; border: 1px solid #000;">
            <p>&nbsp;</p>
            <h3 class="text-center">FEDERAL MEDICAL CENTRE, ABEOKUTA</h3>
            <h4 class="text-center">OCCUPATIONAL THERAPY UNIT</h4>
            <h6 class="text-center">Occupational Therapy Initial Assessment</h6>


            <table class="table" border="1">
              @foreach($data as $row)
              <tr>
                <th>Name</th>
                <td>{{ $row->name }}</td>
              </tr>
              <tr>
                <th>Date of Birth</th>
                <td>{{ $row->dob }}</td>
              </tr>
              <tr>
                <th>Treatment Dx</th>
                <td> {{ $row->treatment }} </td>
              </tr>
              <tr>
                <th>Past Medical/surgical Hx</th>
                <td>{{ $row->history }}</td>
              </tr>
              <tr>
                <th>Medications</th>
                <td>{{ $row->medication }}</td>
              </tr>
              <tr>
                <th>Date of Initial Assessment</th>
                <td>{{ $row->dt }}</td>
              </tr>
              <tr>
                <th>Occupational Profile</th>
                <td>{{ $row->occupational }}</td>
              </tr>
              @endforeach
            </table>


            <p>&nbsp;</p>
            <table class="table" border="1">
              <tr>
                <th colspan="3" class="text-center">Activities of Daily living(ADL Status)</th>
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th>Comment</th>
              </tr>

                @foreach($data2 as $row)
                <tr>
                <th>{{ $row->issue }}</th>
                <td>{{ $row->answer }}</td>
                <td>{{ $row->comment }}</td>
              </tr>
              @endforeach

            </table>
            <p>&nbsp;</p>
            <table class="table" border="1">
              <tr>
                <th colspan="3" class="text-center">Instrumental ADL Status</th>
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th>Comment</th>
              </tr>
              
              @foreach($data3 as $row)
                <tr>
                <th>{{ $row->issue }}</th>
                <td>{{ $row->answer }}</td>
                <td>{{ $row->comment }}</td>
              </tr>
              @endforeach

               </table>
              <p>&nbsp;</p>
              <table class="table" border="1">
                <tr>
                  <th colspan="3" class="text-center">Work/Leisure participation</th>
                </tr>
                <tr>
                  <th></th>
                  <th></th>
                  <th>Comment</th>
                </tr>
                
@foreach($data4 as $row)
                <tr>
                <th>{{ $row->issue }}</th>
                <td>{{ $row->answer }}</td>
                <td>{{ $row->comment }}</td>
              </tr>
              @endforeach

                  </table>
                <p>&nbsp;</p>
                <table class="table" border="1">
                  <tr>
                    <th colspan="3" class="text-center">Client Factors</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Comment</th>
                  </tr>

                  @foreach($data5 as $row)
                <tr>
                <th>{{ $row->issue }}</th>
                <th>{{ $row->tp }}</th>
                <td>{{ $row->answer }}</td>
                <td>{{ $row->comment }}</td>
              </tr>
              @endforeach
                  
                    </table>
                  <p>&nbsp;</p>
                  <table class="table" border="1">
                    <tr>
                      <th colspan="3" class="text-center">Performance skills</th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                      <th>Comment</th>
                    </tr>

                    @foreach($data6 as $row)
                <tr>
                <th>{{ $row->issue }}</th>
                <td>{{ $row->answer }}</td>
                <td>{{ $row->comment }}</td>
              </tr>
              @endforeach    

                      </table>
                    <p>&nbsp;</p>
                    <div class="container">

                      @foreach($data7 as $row)

                      <p><b>Patient/family goals: </b> {{ $row->patient }}</p>
                      <p><b>Analysis of Occupational performance: </b> {{ $row->analysis }}</p>
                      <p><b>Short-term goals: </b> {{ $row->short_goal }}</p>
                      <p><b>Long-term goals: </b> {{ $row