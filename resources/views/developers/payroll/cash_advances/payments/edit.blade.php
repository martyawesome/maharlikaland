@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Edit Cash Advance Payment
      <small><a href="{{ URL::route('cash_advances_payments') }}">Cash Advances Payments</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Cash Advances</li>
      <li>Cash Advance Payments</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($cash_advance) !!}
      @include('includes.payroll.cash_advance') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var cash_advance_id = "<?php echo $cash_advance->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_cash_advance_payment.js') }}"></script>
@stop


