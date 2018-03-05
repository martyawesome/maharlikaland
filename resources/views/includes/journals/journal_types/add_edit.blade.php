<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
      {!! Form::label('type', 'Type*'); !!}
      {!! Form::text('type', $journal_type->type, ['class' => 'form-control']) !!}
      {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>