<div class="box box-primary">
  <div class="box-body">
    <div class="form-group">
      {!! Form::label('type', 'Journal Type'); !!}
      {!! Form::select('type', $journal_types, $journal->journal_type_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
      {!! Form::label('date', 'Date (yyyy-mm-dd)*'); !!}
      {!! Form::text('date', $journal->date, ['class' => 'form-control pull-right', 'style' => 'margin-bottom:15px;']) !!}
      {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('entry') ? ' has-error' : '' }}">
      {!! Form::label('entry', 'Entry*'); !!}
      {!! Form::textarea('entry', $journal->entry, ['class' => 'form-control']) !!}
      {!! $errors->first('entry', '<span class="help-block">:message</span>') !!}
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