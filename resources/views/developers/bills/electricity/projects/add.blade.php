@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New Electricity Bill for {{ $project->name }}
      <small><b><a href="{{ URL::route('bills_electricity_project', $project->slug) }}">Back</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Electricity</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($electricity_bill, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.bills.electricity.add_edit_project')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    $('#date_covered').datepicker({
      format: "MM, yyyy",
      startView: "months", 
      minViewMode: "months"
    });
  </script>
@stop