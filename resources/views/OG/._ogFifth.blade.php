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

        <form method="post" action="ortPage9">

          @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </div>
          @endif
          
          {{ csrf_field() }}

          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Joint Mobility</label>
            <input type="text" name="joint" required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Palpation</label>
            <input type="text" name="palpation"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Functional Assesment</label>
            <textarea name="functional" class="form-control" placeholder="next line action following patient response to previous treatment"></textarea>
          </div>
          <div class="panel panel-primary  w3-card w3-sand">
            <header class="w3-container w3-blue">
              <h4>Gait   </h4>
            </header>
            <div class="panel-body">
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Weight Bearing</label>
                <select name="weight" class="form-control col-sm-4">
                  <option value="partial">partial</option>
                  <option value="full">full</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Assistive Device</label>
                <input type="text" name="assistive"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-4">
                <label for="exampleInputEmail1">Assistance</label>
                <input type="text" name="assistance"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Distance</label>
                <input type="text" name="distance"  required class="form-control" id="exampleInputEmail1" />
              </div>
              <div class="form-group col-sm-6">
                <label for="exampleInputEmail1">Proprioception</label>
                <input type="text" name="pro"  required class="form-control" id="exampleInputEmail1" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Clinical impression</label>
            <textarea name="clinical" class="form-control" placeholder="to write  things being done before and they cant do now"></textarea>
          </div>
          <div class="form-group">
            <h4 class="w3-blue">Treatment Plan</h4>
            <label for="exampleInputEmail1">Treatment provided during this visit</label>
            <input type="text" name="treatment"  required class="form-control" id="exampleInputEmail1" />
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Response to treatment</label>
            <select name="response" class="form-control">
              <option value="well tolerated">well tolerated</option>
              <option value="not tolerated">not tolerated</option>
              <option value="complain">complain</option>
            </select>
          </div>
          <div class="form-group col-sm-6">
            <label for="exampleInputEmail1">Rehab Potential/Prognosis</label>
            <input type="text" na