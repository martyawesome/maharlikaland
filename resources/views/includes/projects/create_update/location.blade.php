<div>
  <h2><small><b>Location</b></small></h2>
  <div class="box box-warning">
    <div class="box-body">
      <div class="form-group">
        {!! Form::label('province', 'Province'); !!}
        {!! Form::select('province', $provinces_list, $project_location->province_id, ['class' => 'form-control', 'onchange' => 'onProvinceChange(this,'.$cities_municipalities.')']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('city_municipality', 'City/Municipality'); !!}
        {!! Form::select('city_municipality', [], $project_location->city_municipality_id, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('barangay', 'Barangay'); !!}
        {!! Form::text('barangay', $project_location->barangay, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('street', 'Street'); !!}
        {!! Form::text('street', $project_location->street, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group{{ $errors->has('coordinates') ? ' has-error' : '' }}">
        {!! Form::label('coordinates', 'Coordinates'); !!} <a href="http://www.maps.google.com" target="_blank">&nbsp;(Go to Google Maps)</a>
        {!! Form::text('coordinates', $project_location->coordinates, ['class' => 'form-control']) !!}
        {!! $errors->first('coordinates', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group">
        {!! Form::label('remarks', 'Remarks'); !!}
        {!! Form::textarea('remarks', $project_location->remarks, ['class' => 'form-control', 'rows' => '3']); !!}
      </div>
    </div>
  </div>
</div>