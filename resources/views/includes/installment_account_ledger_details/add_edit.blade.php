<div class="box box-primary">
  <div class="box-body">
    <div class="form-group">
      {!! Form::label('payment_type', 'Payment Type*'); !!}
      {!! Form::select('payment_type', $payment_types, $ledger_detail->payment_type_id, ['class' => 'form-control']); !!}
    </div>
    <div class="form-group" id="payment_date_container">
      {!! Form::label('payment_date', 'Payment Date*'); !!}
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        @if(old('payment_date'))
          <input type="text" name="payment_date" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! old('payment_date') !!}" data-mask>
        @else
          @if($ledger_detail->payment_date != null and $ledger_detail->payment_date != "0000-00-00")
            <input type="text" name="payment_date" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! $ledger_detail->payment_date !!}" data-mask>
          @else
            <input type="text" name="payment_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        @endif
        {!! $errors->first('payment_date', '<span class="help-block">:message</span>') !!}
       </div>
    </div>
    <div class="form-group{{ $errors->has('amount_paid') ? ' has-error' : '' }}" id="amount_paid_container">
      {!! Form::label('amount_paid', 'Amount Paid*'); !!}
      {!! Form::text('amount_paid', $ledger_detail->amount_paid, ['class' => 'form-control']) !!}
      {!! $errors->first('amount_paid', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group" id="ma_covered_date_container">
      {!! Form::label('ma_covered_date', 'Month Covered by Payment'); !!}
      {!! Form::text('ma_covered_date', null, array('type' => 'text', 'class' => 'form-control datepicker','id' => 'ma_covered_date')) !!}
    </div>
    <!-- <div class="checkbox icheck" id="with_interest_container">
        {!! Form::checkbox('with_interest', null, true, ['class' => 'flat-red']); !!}&nbsp;&nbsp;With Interest
    </div> -->
     <div class="checkbox icheck" id="balloon_payment_container">
        {!! Form::checkbox('balloon_payment', null, false, ['class' => 'flat-red']); !!}&nbsp;&nbsp;Balloon Payment
    </div>
    <div class="form-group{{ $errors->has('or_no') ? ' has-error' : '' }}" id="or_no_container"> 
      {!! Form::label('or_no', 'OR NO'); !!}
      {!! Form::text('or_no', $ledger_detail->or_no, ['class' => 'form-control']) !!}
      {!! $errors->first('or_no', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>