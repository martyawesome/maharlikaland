@extends('admin.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Add Agent
      <small>Agents</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Agents</li>
      <li class="active">Add Agent</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model(null, array('files' => true)) !!}
      @include('includes.agents.add_edit')
      @if(!$user->id)
        @include('includes.users.add_edit')
      @endif
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop

