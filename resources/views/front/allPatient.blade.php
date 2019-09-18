@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

    	<div class="panel panel-primary col-sm-10">
         <div class="panel-heading">DIAGNOSIS / REASON FOR REFERRAL</div>
         <div class="panel-body">

            @if(Session::has('error'))
            <div class="alert alert-danger">
               {{ Session::get('error') }}
           </div>
           @endif

           <form method="post" action="neuPage2">

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
                            <span class="input-group-addon" id="basic-addon1">H<sub>x</sub>PC</span>
                            <input type="text" name="hcp" class="form-control" placeholder="hcp" aria-describedby="basic-addon1" required=""/>
   