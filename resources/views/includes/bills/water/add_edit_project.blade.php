<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('date_covered') ? ' has-error' : '' }}">
      {!! Form::label('date_covered', 'Date Covered*'); !!}
      {!! Form::text('date_covered', null, array('type' => 'text', 'class' => 'form-control datepicker','id' => 'date_covered')) !!}
      {!! $errors->first('date_covered', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('consumption') ? ' has-error' : '' }}">
      {!! Form::label('consumption', 'Consumption*'); !!}
      {!! Form::text('consumption', $water_bill->consumption, ['class' => 'form-control']) !!}
      {!! $errors->first('consumption', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('bill') ? ' has-error' : '' }}">
      {!! Form::label('bill', 'Bill Amount*'); !!}
      {!! Form::text('bill', $water_bill->bill, ['class' => 'form-control']) !!}
      {!! $errors->first('bill', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('remarks', 'Remarks'); !!}
      {!! Form::textarea('remarks', $water_bill->remarks, ['class' => 'form-control', 'rows' => '3']); !!}
    </div>
  </div> 
</div>