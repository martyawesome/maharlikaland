<div class="box box-primary">
  <div class="box-body">
    <div class="form-group date{{ $errors->has('date') ? ' has-error' : '' }}">
      {!! Form::label('date', 'Date (yyyy-mm-dd)*'); !!}
      {!! Form::text('date', $holiday->date, ['class' => 'form-control']) !!}
      {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
      {!! Form::label('name', 'Name*'); !!}
      {!! Form::text('name', $holiday->name, ['class' => 'form-control']) !!}
      {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
      {!! Form::label('type', 'Type*'); !!}
      {!! Form::select('type', array('1' => 'Regular Working', '2' => 'Special Non-working'), $holiday->type, ['class' => 'form-control']) !!}
      {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
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