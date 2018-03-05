@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New User
      <small>Users</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Users</li>
      <li class="active">New Users</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($user, array('files' => true)) !!}
      @include('includes.users.add_edit_user_type')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop