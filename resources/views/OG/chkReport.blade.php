@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-12" style="overflow-y: scroll;">
      <div class="panel-heading">RANGE OF MOTION</div>
      <div class="panel-body">

        @if(Session::has('error'))
        <div class="alert alert-danger">
          {{ Session::get('error') }}
        </div>
        @endif

        <form method="post" action="ortPage6">

          @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
          </div>
          @endif
          
          {{ csrf_field() }}

          <table class="table" border="1">
            <tr>
              <th></th>
              <th colspan="3" style="text-align: center;">L Lower extremity</th>
              <th colspan="3"