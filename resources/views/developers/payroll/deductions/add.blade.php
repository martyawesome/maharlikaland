@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Payroll Deduction
      <small><a href="{{ URL::route('payroll_deductions') }}">Payroll Deductions</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Payroll Deductions</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($deduction) !!}
      @include('includes.payroll.deduction') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop


