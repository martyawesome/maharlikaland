@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit Water Bill for {{ $project->name }}
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Water</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($water_bill, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.bills.water.add_edit_project')
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