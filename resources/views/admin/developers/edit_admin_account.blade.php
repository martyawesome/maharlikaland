@extends('admin.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Edit Admin Developer Account
      <small>Developers</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Developers</li>
      <li>All Developers</li>
      <li>Edit Developer</li>
      <li>{{ $developer->name }}</li>
      <li class="active">Edit Admin Account</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($user, array('files' => true)) !!}
      @include('includes.users.add_edit')
      <div class="box-footer">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary'])!!}
        <!-- <div id="deleteAccountButton" class="btn btn-danger">Delete</div> -->
      </div>
    {!! Form::close() !!}
  </section>
@stop