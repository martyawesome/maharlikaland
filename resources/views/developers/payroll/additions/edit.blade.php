@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Payroll Addition
      <small><a href="{{ URL::route('payroll_additions') }}">Payroll Additions</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Payroll Additions</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($addition) !!}
      @include('includes.payroll.addition') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var addition_id = "<?php echo $addition->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_payroll_addition.js') }}"></script>
@stop


