@extends('admin.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Add Developer
      <small>Developers</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Developers</li>
      <li class="active">Add Developer</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model(null, array('files' => true)) !!}
      @include('includes.developers.add_edit')
      @include('includes.users.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop