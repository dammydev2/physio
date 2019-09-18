@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-8">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="ogPage5">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					<div class="form-group">
						<label for="exampleInputEmail1">How would you classify your general health?</label>
						<label>Good: <input type="radio" required="" name="health" value="Good" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Fair: <input type="radio" required="" name="health" value="Fair" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Poor: <input type="radio" required="" name="health" value="Poor" required></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>Please check all that apply:</label>
						<label>Allergies <input type="checkbox" name="apply[]" value="Allergies"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Multiple Sclerosis <input type="checkbox" name="apply[]" value="Multiple Sclerosis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Rheumatic fever <input type="checkbox" name="apply[]" value="Rheumatic fever"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Asthma /Breathing Difficulties <input type="checkbox" name="apply[]" value="Asthma /Breathing Difficulties"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Enlarged Glands <input type="checkbox" name="apply[]" value="Enlarged Glands"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Nausea/Vomiting <input type="checkbox" name="apply[]" value="Nausea/Vomiting"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Ringing of the ears <input type="checkbox" name="apply[]" value="Ringing of the ears"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Bronchitis <input type="checkbox" name="apply[]" value="Bronchitis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Fever  <input type="checkbox" name="apply[]" value="Fever"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Night Pain <input type="checkbox" name="apply[]" value="Night Pain"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Seizures/Epilepsy <input type="checkbox" name="apply[]" value="Seizures/Epilepsy"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Cancer <input type="checkbox" name="apply[]" value="Cancer"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Head injury <input type="checkbox" name="apply[]" value="Head injury"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Osteoarthritis <input type="checkbox" name="apply[]" value="Osteoarthritis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Sinusitis <input type="checkbox" name="apply[]" value="Sinusitis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Chest pain/Angina <input type="checkbox" name="apply[]" value="Chest pain/Angina"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Headaches <input type="checkbox" name="apply[]" value="Headaches"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Osteoporosis <input type="checkbox" name="apply[]" value="Osteoporosis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Smoking History <input type="checkbox" name="apply[]" value="Smoking History"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Heart Disease <input type="checkbox" name="apply[]" value="Heart Disease"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Pacemaker <input type="checkbox" name="apply[]" value="Pacemaker"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Stroke/TIA <input type="checkbox" name="apply[]" value="Stroke/TIA"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Chronic Colds <input type="checkbox" name="apply[]" value="Chronic Colds"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>High Blood Pressure <input type="checkbox" name="apply[]" value="High Blood Pressure"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Parkinson’s Disease <input type="checkbox" name="apply[]" value="Parkinson’s Disease"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Surgeries <input type="checkbox" name="apply[]" value="Surgeries"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Chronic Laryngitis <input type="checkbox" name="apply[]" value="Chronic Laryngitis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Hypoglycaemia <input type="checkbox" name="apply[]" value="Hypoglycaemia"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Physical Abnormalities <input type="checkbox" name="apply[]" value="Physical Abnormalities"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Thyroid Disease <input type="checkbox" name="apply[]" value="Thyroid Disease"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Measles <input type="checkbox" name="apply[]" value="Measles"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Depression <input type="checkbox" name="apply[]" value="Depression"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Meningitis <input type="checkbox" name="apply[]" value="Meningitis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Polio <input type="checkbox" name="apply[]" value="Polio"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Tuberculosis <input type="checkbox" name="apply[]" value="Tuberculosis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Diabetes: Type I or  II <input type="checkbox" name="apply[]" value="Diabetes: Type I or  II"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Metal Implants <input type="checkbox" name="apply[]" value="Metal Implants"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Pregnancy[Currently] <input type="checkbox" name="apply[]" value="Pregnancy[Currently]"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Whooping Cough <input type="checkbox" name="apply[]" value="Whooping Cough"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Dizziness/Fainting <input type="checkbox" name="apply[]" value="Dizziness/Fainting"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Mumps <input type="checkbox" name="apply[]" value="Mumps"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>Rheumatoid Arthritis <input type="checkbox" name="apply[]" value="Rheumatoid Arthritis"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<div class="form-group">
						<label>Is there any other information concerning your medical history that we should know about?</label>
						<textarea class="form-control" required="" name="information"></textarea>
					</div>
					<div class="form-group">
						<label>Have you ever purchased or rented Durable Medical Equipments, Orthotocs, Prosthetics. Or Supplies?</label><br>
						<label>Yes <input type="radio" required="" name="durable" value="Yes"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label>No <input type="radio" required="" name="durable" value="No"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<div class="form-group">
						<label>Explain</label>
						<textarea class="form-control" required="" name="explain"></textarea>
					</div>
					<div class="form-group">
						<input type="checkbox" required name=""> I have reviewed the information provided and information are complete
					</div>
					<div class="form-group">
						<label>Therapist Name</label>
						<input type="physio" name="physio" class="form-control" readonly value="{{ \Auth::User()->name }}">
					</div>
					<div class="form-group">
						<label>Please List Previous Medical History or Co-Morbidities that May Impact Rate of Recovery:</label>
						<textarea class="form-control" required="" name="info"></textarea>
					</div>

					<input type="submit" name="" value="Continue" class="btn btn-primary">

				</form>

			</div>
		</div>


	</div>
</div>
@endsection
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             @extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-5">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="ogPage1">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					@foreach($data as $row)
					<div class="form-group">
						<label for="exampleInputEmail1">Patient Name</label>
						<input type="text" name="name" required readonly value=" {{ $row->name }} " class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">physio Number</label>
						<input type="text" name="physio_num"  readonly value=" {{ $row->physio }} " class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Date of birth</label>
						<input type="text" name="DOB"  required readonly value="{{ $row->dob }}" class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Date of evaluation</label>
						<input type="date" name="dt"  required  class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Marital status</label>
						<input type="text" required="" name="marital"  placeholder="Enter Marital status" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Diagnosis</label>
						<input type="text" name="diagnosis"  placeholder="" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Duaration of marriage</label>
						<input type="text" name="duration"  placeholder="e.g. 3 years" required class="form-control" id="exampleInputEmail1" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Address</label>
						<input type="text" name="address"  placeholder="Enter Address" required class="form-control" id="exampleInputEmail1" />
					</div>
					<input type="text" name="gender" style="display: none;" value="{{ $row->gender }}" class="form-control" id="exampleInputEmail1" />

					<input type="hidden" name="physio" value="{{ \Auth::User()->name }}">
					@endforeach

					<input type="submit" name="" value="Continue" class="btn btn-primary">

				</form>

			</div>
		</div>


	</div>
</div>
@endsection
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 @extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="panel panel-primary col-sm-6">
			<div class="panel-heading">Add Report (Occupational Theraphy)</div>
			<div class="panel-body">

				@if(Session::has('error'))
				<div class="alert alert-danger">
					{{ Session::get('error') }}
				</div>
				@endif

				<form method="post" action="ogPage4">

					@if ($errors->any())
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</div>
					@endif
					
					{{ csrf_field() }}

					<div class="form-group">
						<label for="exampleInputEmail1">Gravida & Parity</label>
						<input type="text" class="form-control" name="parity">
						<p>&nbsp;</p>
					</div>
					<div class="form-group">
						<label>Number of living children</label><br>
						No of Boys<input type="text" name="boys"><br>
						No of Girls<input type="text" name="girls"><br>
						No of Twins<input type="text" name="twins">
					</div>
					<div class="form-group">
						<label>Multiple pregnancies</label><br>
						Yes <input type="radio" name="multiple" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						No <input type="radio" name="multiple" value="no">
					</div>
					<div class="form-group">
						<label>Duration between pregnancies</label>
						<input type="text" name="duration" class="form-control">
					</div>
					<div class="form-group">
						<label>Were there any complications during pregnancy?</label><br>
						Yes <input type="radio" name="complication" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						No <input type="radio" name="complication" value="no">
					</div>
					<div class="form-group">
						<label>Comment</label>
						<textarea class="form-control" name="comment"></textarea>
					</div>
					<div class="form-group">
						<label>Was the pregnancy full term?</label><br>
						Yes <input type="radio" name="term" value="Yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						No <input type="radio" name="