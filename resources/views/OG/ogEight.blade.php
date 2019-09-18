@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">

    <div class="panel panel-primary col-sm-5">
      <div class="panel-heading">*******</div>
      <div class="panel-body">

        @if(Session::has('error'))
        <div class="alert alert-danger">
          {{ Session::get('error') }}
        </div>
        @endif

        <form method="post" action="ortPage10">

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
                        <th colspan="2" class="text-center">Mobility</th>
                      </tr>
                      <tr>
                        <th colspan="2"></th>
                      </tr>
                      <tr>
                        <td>Rolling<input type="text" name="test[]" value="Rolling" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Scooting<input type="text" name="test[]" value="Scooting" readonly style="display: none;"></td>
                        <td>
                          <select name="answer[]">
                            <option value="independent">independent</option>
                            <option value="standby assist">standby assist</option>
                            <option value="contact guard assist">contact guard assist</option>
                            <option value="Minimal assistance">Minimal assistance</option>
                            <option value="moderate assistance">moderate assistance</option>
                            <option value="maximum assistance">maximum assistance</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Supine to Sit<input type="text" name="tes