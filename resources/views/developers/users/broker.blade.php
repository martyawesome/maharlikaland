@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
    	Broker Details
    	<small>Users</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Users</li>
      <li class="active">Broker Details</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($agent, array('files' => true)) !!}
      @include('includes.agents.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop