<div class="box box-default">
  <div class="box-body">
    <div class="form-group{{ $errors->has('joint_venture') ? ' has-error' : '' }}">
      {!! Form::label('joint_venture', 'Joint Venture*'); !!}
      {!! Form::text('joint_venture', $joint_venture->name, ['class' => 'form-control']) !!}
      {!! $errors->first('joint_venture', '<span class="help-block">:message</span>') !!}
    </div>
  </div>
</div> 