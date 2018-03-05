<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
      {!! Form::label('user', 'User*'); !!}
      {!! Form::select('user', $users, $salary_rate->user_id, ['class' => 'form-control']) !!}
      {!! $errors->first('user', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
      {!! Form::label('rate', 'Rate*'); !!}
      {!! Form::text('rate', $salary_rate->rate, ['class' => 'form-control']) !!}
      {!! $errors->first('rate', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>