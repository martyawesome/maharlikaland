@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Cash Advance Credit 
      <small><a href="{{ URL::route('cash_advances_credit') }}">Cash Advances Credit</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Cash Advances</li>
      <li>Credit</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($cash_advance) !!}
      @include('includes.payroll.cash_advance') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop


