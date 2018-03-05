@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Create User Account
      <small>Users</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Buyers</li>
      <li class="active">Create User Account</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($user, array('files' => true)) !!}
      @include('includes.users.add_edit')
      
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop