<div class="box box-default">
  <div class="box-body">
    <div class="form-group{{ $errors->has('amenity') ? ' has-error' : '' }}">
      {!! Form::label('amenity', 'Amenity*'); !!}
      {!! Form::text('amenity', $amenity->amenity, ['class' => 'form-control']) !!}
      {!! $errors->first('amenity', '<span class="help-block">:message</span>') !!}
    </div>
  </div>
</div> 