<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('property') ? ' has-error' : '' }}">
      {!! Form::label('property', 'Property'); !!}
      {!! Form::select('property', $properties, $property->id, ['class' => 'form-control']); !!}
      {!! $errors->first('property', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('tcp') ? ' has-error' : '' }}">
      {!! Form::label('tcp', 'Total Contract Price (TCP)*'); !!}
      @if($add)
        {!! Form::text('tcp', number_format($ledger->tcp, 2, '.', ','), ['class' => 'form-control']) !!}
      @else
        {!! Form::text('tcp', number_format($ledger->tcp, 2, '.', ','), ['class' => 'form-control']) !!}
      @endif
      {!! $errors->first('tcp', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('years_to_pay') ? ' has-error' : '' }}">
      {!! Form::label('years_to_pay', 'Years to Pay*'); !!}
      @if($restructure_allowed)
        {!! Form::text('years_to_pay', $ledger->years_to_pay, ['class' => 'form-control']) !!}
      @else
        {!! Form::text('years_to_pay', $ledger->years_to_pay, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
      @endif
      {!! $errors->first('years_to_pay', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('reservation_fee') ? ' has-error' : '' }}">
      {!! Form::label('reservation_fee', 'Reservation Fee*'); !!}
      {!! Form::text('reservation_fee', number_format($ledger->reservation_fee, 2, '.', ','), ['class' => 'form-control']) !!}
      {!! $errors->first('reservation_fee', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('dp_percentage') ? ' has-error' : '' }}">
      {!! Form::label('dp_percentage', 'Downpayment (DP) Percentage*'); !!}
      {!! Form::text('dp_percentage', $ledger->dp_percentage, ['class' => 'form-control']) !!}
      {!! $errors->first('dp_percentage', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('dp') ? ' has-error' : '' }}">
      {!! Form::label('dp', 'Downpayment (DP)*'); !!}
      {!! Form::text('dp', number_format($ledger->dp, 2, '.', ',') , ['class' => 'form-control','readonly' => 'readonly']) !!}
      {!! $errors->first('dp', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('dp_discount', 'Downpayment (DP) Discount'); !!}
      {!! Form::text('dp_discount', number_format($ledger->dp_discount, 2, '.', ',') , ['class' => 'form-control']) !!}
    </div>
    <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
      {!! Form::label('due_date', 'Due Date (1 to 30)*'); !!}
      {!! Form::text('due_date', $ledger->due_date, ['class' => 'form-control']) !!}
      {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('mo_interest') ? ' has-error' : '' }}">
      {!! Form::label('mo_interest', 'Monthly Interest (example 2%)*'); !!}
      @if($restructure_allowed)
        {!! Form::text('mo_interest', $ledger->mo_interest, ['class' => 'form-control']) !!}
      @else
        {!! Form::text('mo_interest', $ledger->mo_interest, ['class' => 'form-control','readonly' => 'readonly']) !!}
      @endif
      {!! $errors->first('mo_interest', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('mo_amortization') ? ' has-error' : '' }}">
      {!! Form::label('mo_amortization', 'Monthly Amortization*'); !!}
      {!! Form::text('mo_amortization', number_format($ledger->mo_amortization, 2, '.', ','), ['class' => 'form-control', 'readonly' => 'readonly']) !!}
      {!! $errors->first('mo_amortization', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('tct_no', 'TCT Number'); !!}
      {!! Form::text('tct_no', $ledger->tct_no, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group{{ $errors->has('balance') ? ' has-error' : '' }}">
      {!! Form::label('balance', 'Balance*'); !!}
      {!! Form::text('balance', number_format($ledger->balance, 2, '.', ','), ['class' => 'form-control', 'readonly' => 'readonly']) !!}
      {!! $errors->first('balance', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('contract_date', 'Contract Date'); !!}
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        @if(old('contract_date'))
          <input type="text" name="contract_date" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! old('contract_date') !!}" data-mask>
        @else
          @if($ledger->contract_date != null and $ledger->contract_date != "0000-00-00")
            <input type="text" name="contract_date" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! $ledger->contract_date !!}" data-mask>
          @else
            <input type="text" name="contract_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        @endif
       </div>
    </div>
  </div> 
</div>
<div class="box box-primary">
  <div class="box-body">
    <div class="form-group">
      {!! Form::label('bank_finance', 'Bank Finance Loan'); !!}
      {!! Form::text('bank_finance', $ledger->bank_finance, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('bank_finance_months', 'Difference Payable in Months'); !!}
      {!! Form::text('bank_finance_months', $ledger->bank_finance_months, ['class' => 'form-control']) !!}
    </div>
  </div>
</div>