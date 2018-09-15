@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit Electricity Bill for {{ $property->name }}
      <small><b><a href="{{ URL::route('bills_electricity_project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Electricity</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($electricity_bill_detail, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.bills.electricity.add_edit_property')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    var cost_per_consumption = "<?php echo ($electricity_bill->bill / $electricity_bill->consumption) * config('constants.ELECTRICITY_SOURCE_PERCENTAGE'); ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/edit_bill_electricity_detail.js') }}"></script>

@stop