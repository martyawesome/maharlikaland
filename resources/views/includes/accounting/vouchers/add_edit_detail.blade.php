<div class="box box-primary">
  <div class="box-body">
    <div class="form-group">
      {!! Form::label('account_title', 'Account Title*'); !!}
      {!! Form::select('account_title', $account_titles, $voucher_detail->account_title_id, ['class' => 'form-control', 'onchange' => 'onPropertyTypeChange(this)']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('property', 'Property'); !!}
      {!! Form::select('property', $properties, $voucher_detail->property_id, ['class' => 'form-control', 'onchange' => 'onPropertyTypeChange(this)']) !!}
    </div>
  	<div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
      {!! Form::label('amount', 'Amount*'); !!}
      {!! Form::text('amount', $voucher_detail->amount, ['class' => 'form-control']) !!}
      {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('remarks', 'Remarks'); !!}
      {!! Form::textarea('remarks', $voucher_detail->remarks, ['class' => 'form-control', 'rows' => '3']); !!}
    </div>
  </div> 
</div>