@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Edit Salary Rate
    </h1>
    <ol class="breadcrumb">
      <li>Salary Rates</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($salary_rate) !!}
      @include('includes.payroll.salary_rate') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var salary_rate_id = "<?php echo $salary_rate->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_salary_rate.js') }}"></script>
@stop


