@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">

   <div class="panel panel-primary col-sm-10">
     <div class="panel-heading">ACUTE NEUROLOGICAL ASSESSMENT</div>
     <div class="panel-body">

      @if(Session::has('error'))
      <div class="alert alert-danger">
       {{ Session::get('error') }}
     </div>
     @endif

     <form method="post" action="neuPage3">

       @if ($errors->any())
       <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
      </div>
      @endif

      {{ csrf_field() }}

      <div class="form-group">
       <div class="col-lg-6 ">
        <div class="form-group">
          <div class="input-group w3_w3layouts col-lg-12">
            <span class="input-group-addon" id="basic-addon1">LEVEL OF ALERTNESS</span>
            <input type="text" name="alert" value="" class="form-control" aria-describedby="basic-addon1" required=""  / > 
                    <!--Alert <input type="checkbox" name="alert1" value="alert">
                    Voice <input type="checkbox" name="alert2" value="voice">
                    Pain <input type="checkbox" name="alert3" value="pain">
                    unresponsive <input type="checkbox" name="alert4" value="unresponsive">
                    <input type="text" name="alert5" placeholder="enter more alert here">-->
                  </div>
                </div>
    