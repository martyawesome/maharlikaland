<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
      {!! Form::label('user', 'User*'); !!}
      {!! Form::select('user', $users, $cash_advance->user_id, ['class' => 'form-control']) !!}
      {!! $errors->first('user', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
      {!! Form::label('amount', 'Amount*'); !!}
      {!! Form::text('amount', $cash_advance->amount, ['class' => 'form-control']) !!}
      {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group date{{ $errors->has('date') ? ' has-error' : '' }}">
      {!! Form::label('date', 'Date (yyyy-mm-dd)*'); !!}
      {!! Form::text('date', $cash_advance->date, ['class' => 'form-control pull-right']) !!}
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