@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-10">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        @if(Session::has('error'))
        <div class="alert alert-danger">
          {{ Session::get('error') }}
        </div>
        @endif

        <form method="post" action="ortPage7">

          @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </div>
          @endif
          
          {{ csrf_field() }}

          <div class="panel panel-primary  w3-card w3-sand">
            <header class="w3-container w3-blue">
              <h4>   </h4>
            </header>
            <div class="panel-body">

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Scapular mobility</label>
                <input type="text" name="mobility" required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Neurological</label>
                <input type="text" name="neuro"  required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Paresthesias</label>
                <input type="text" name="paresthesias"  required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Sensation</label>
                <input type="text" name="sensation"  required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Proprioception</label>
                <input type="text" name="prop"  required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">tone</label>
                <select name="tone" class="form-control">
                  <option value="hypertonic">hypertonic</option>
                  <option value="hypotonic">hypotonic</option>
                  <option value="normal tonic">normal tonic</option>
                </select>
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Reflexes</label>
                <input type="text" name="reflex"  required class="form-control" id="exampleInputEmail1" />
              </div>

              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Other</label>
                <input type="text" name="other"  required class="form-control" id="exampleInputEmail1" />
              </div>
            </div>
          </div>

        </div>
        <div class="col-sm-12">
          <div class="form-group" style="background: #fff;">
            <p>&nbsp;</p>
            <label for="exampleInputEmail1"><b>Posture</b></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="No Abnormality" />No Abnormality &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Forward Head" />Forward Head &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Genu Valgum/Varus/Recurvatum" />Genu Valgum/Varus/Recurvatum &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Kyphosis" />Kyphosis &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Protracted Shoulders" />Protracted Shoulders &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Ankle Pronation/Supination" />Ankle Pronation/Supination &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="Posture[]"  value="Scoliosis" />Scoliosis &nbsp;&nbsp;&nbsp;
      