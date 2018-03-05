<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('date_covered') ? ' has-error' : '' }}">
      {!! Form::label('date_covered', 'Date Covered*'); !!}
      {!! Form::text('date_covered', $water_bill_detail->date_covered, array('type' => 'text', 'class' => 'form-control datepicker','id' => 'date_covered','readonly' => 'readonly')) !!}
      {!! $errors->first('date_covered', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('consumption') ? ' has-error' : '' }}">
      {!! Form::label('consumption', 'Consumption*'); !!}
      {!! Form::text('consumption', number_format($water_bill_detail->consumption, 4, '.', ','), ['class' => 'form-control']) !!}
      {!! $errors->first('consumption', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('bill') ? ' has-error' : '' }}">
      {!! Form::label('bill', 'Bill Amount'); !!}
      {!! Form::text('bill', number_format($water_bill_detail->bill, 4, '.', ','), ['class' => 'form-control','readonly' => 'readonly']) !!}
      {!! $errors->first('bill', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('payment', 'Payment'); !!}
      {!! Form::text('payment', number_format($water_bill_detail->payment, 4, '.', ','), ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('date_payment', 'Date of Payment'); !!}
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        @if(old('date_payment'))
          <input type="text" name="date_payment" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" value="{!! old('date_payment') !!}" data-mask>
        @else
          @if($water_bill_detail->date_payment != null and $water_bill_detail->date_payment != "0000-00-00")
            <input type="text" name="date_payment" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" value="{!! $water_bill_detail->date_payment !!}" data-mask>
          @else
            <input type="text" name="date_payment" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        @endif
        {!! $errors->first('date_payment', '<span class="help-block">:message</span>') !!}
       </div>
    </div>
    <div class="form-group">
      {!! Form::label('remarks', 'Remarks'); !!}
      {!! Form::textarea('remarks', $water_bill_detail->remarks, ['class' => 'form-control', 'rows' => '3']); !!}
    </div>
  </div> 
</div>