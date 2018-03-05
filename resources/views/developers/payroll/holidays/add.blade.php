@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Holiday
    </h1>
    <ol class="breadcrumb">
      <li>Holiday</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($holiday) !!}
      @include('includes.payroll.holiday') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop


