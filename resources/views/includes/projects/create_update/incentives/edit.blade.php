<div class="box box-default">
  <div class="box-body">
    <div class="form-group{{ $errors->has('incentive') ? ' has-error' : '' }}">
      {!! Form::label('incentive', 'Incentive*'); !!}
      {!! Form::textarea('incentive', $incentive->incentive, ['class' => 'form-control']) !!}
      {!! $errors->first('incentive', '<span class="help-block">:message</span>') !!}
    </div>
  </div>
</div> 