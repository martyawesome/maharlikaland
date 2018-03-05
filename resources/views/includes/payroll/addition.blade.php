<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
      {!! Form::label('user', 'User*'); !!}
      {!! Form::select('user', $users, $addition->user_id, ['class' => 'form-control']) !!}
      {!! $errors->first('user', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
      {!! Form::label('type', 'Type*'); !!}
      {!! Form::select('type', $addition_types, $addition->payroll_addition_type_id, ['class' => 'form-control']) !!}
      {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
      {!! Form::label('amount', 'Amount*'); !!}
      {!! Form::text('amount', $addition->amount, ['class' => 'form-control']) !!}
      {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group date{{ $errors->has('date') ? ' has-error' : '' }}">
      {!! Form::label('date', 'Date (yyyy-mm-dd)*'); !!}
      {!! Form::text('date', $addition->date, ['class' => 'form-control pull-right']) !!}
      {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>
<script type="text/javascript">
  $(function(){
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>