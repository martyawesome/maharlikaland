@extends('agents.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Add Property
      <small>Properties</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">Add Property</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($property) !!}
      @include('includes.properties.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    window.onload = function() {
      onPropertyTypeChange(document.getElementById("property_type"));
      onFloorsChange(document.getElementById("floors"), <?php echo count($floors)?>);
      onProvinceChange(document.getElementById("province"), <?php echo $cities_municipalities?>);
    };
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/add_property.js') }}"></script>

@stop