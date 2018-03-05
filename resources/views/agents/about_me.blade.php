@extends('agents.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      About Me
      <small>{{ $user->first_name }} {{ $user->last_name }}</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>About Me</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($about_me) !!}
      @include('includes.about_me')
      <div class="box-footer">
        {!! Form::submit('Update', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop