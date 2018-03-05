@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit Water Bill for {{ $property->name }}
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Water</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($water_bill_detail, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.bills.water.add_edit_property')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    var cost_per_consumption = "<?php echo ($water_bill->bill / $water_bill->consumption) * config('constants.WATER_SOURCE_PERCENTAGE'); ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/edit_bill_water_detail.js') }}"></script>

@stop