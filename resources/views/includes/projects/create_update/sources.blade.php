<div>
  <h2><small><b>Sources</b></small></h2>
  <div class="box box-warning">
    <div class="box-body">
      <div class="form-group{{ $errors->has('electricity_source') ? ' has-error' : '' }}">
        {!! Form::label('electricity_source', 'Electricity'); !!}
        {!! Form::text('electricity_source', $electricity_source->electricity_source, ['class' => 'form-control']) !!}
        {!! $errors->first('electricity_source', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group{{ $errors->has('water_source') ? ' has-error' : '' }}">
        {!! Form::label('water_source', 'Water'); !!}
        {!! Form::text('water_source', $water_source->water_source, ['class' => 'form-control']) !!}
        {!! $errors->first('water_source', '<span class="help-block">:message</span>') !!}
      </div>
    </div>
  </div>
</div>