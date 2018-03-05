@extends('admin.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.admin.delete_account_agent')
  <section class="content-header">
    <h1>
      Edit Broker
      <small>Brokers</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Brokers</li>
      <li class="active">All Brokers</li>
      <li class="active">Edit Broker</li>
      <li class="active">{{ $user->username }}</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model(null, array('files' => true)) !!}
      @include('includes.agents.add_edit')
      @include('includes.users.add_edit')
      <div class="box-footer">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary'])!!}
        <div id="deleteAccountButton" class="btn btn-danger">Delete</div>
      </div>
    {!! Form::close() !!}
  </section>
@stop