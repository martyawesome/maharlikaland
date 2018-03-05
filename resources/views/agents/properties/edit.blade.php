@extends('agents.base_dashboard')
@section('content')
  @include('modals.agents.delete_property')
  <section class="content-header">
    <h1>
      Edit Property
      <small>Properties</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">Edit Property</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($property) !!}
      @include('includes.properties.add_edit')
      <div class="box-footer">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    window.onload = function() {
      onPropertyTypeChange(document.getElementById("property_type"));
      onFloorsChange(document.getElementById("floors"), <?php echo count($floors)?>);
      onProvinceChange(document.getElementById("province"), <?php echo $cities_municipalities?>);

      var city_municipality_id = <?php echo $property_location->city_municipality_id; ?>;
      if(city_municipality_id) {
        $("#city_municipality").val(city_municipality_id);
      }

      var floor_areas = <?php echo json_encode($floor_areas); ?>;
      for(var i = 1; i <= floor_areas.length; i++) {
        $('[id^="floor_area_per_floor['+i+'"]').val(floor_areas[i-1].floor_area);
      }

    };
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/add_property.js') }}"></script>

@stop