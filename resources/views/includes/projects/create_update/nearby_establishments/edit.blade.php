<div class="box box-default">
  <div class="box-body">
    <div class="form-group{{ $errors->has('nearby_establishment') ? ' has-error' : '' }}">
      {!! Form::label('nearby_establishment', 'Nearby Establishment*'); !!}
      {!! Form::text('nearby_establishment', $nearby_establishment->nearby_establishment, ['class' => 'form-control']) !!}
      {!! $errors->first('nearby_establishment', '<span class="help-block">:message</span>') !!}
    </div>
  </div>
</div> 