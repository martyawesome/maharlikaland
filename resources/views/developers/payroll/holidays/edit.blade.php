@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Edit Holiday
    </h1>
    <ol class="breadcrumb">
      <li>Payroll</li>
      <li>Holidays</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($holiday) !!}
      @include('includes.payroll.holiday') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var holiday_id = "<?php echo $holiday->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_holiday.js') }}"></script>
@stop


