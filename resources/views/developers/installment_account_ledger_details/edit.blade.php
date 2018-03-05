@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit Ledger Entry
      <small><a href="{{ URL::route('ledger', [$buyer->id, $property->slug]) }}">Back to Ledger</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($ledger_detail, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.installment_account_ledger_details.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}

    <div class="box box-primary">
      <div class="box-body" style="padding-top:0px;">
        <div class="control-group">
          <label class="control-label">Date Last Edited</label>
          <div class="controls readonly">{{ $ledger->updated_at }}</div>
        </div>
        <div class="control-group">
          <label class="control-label">Last Edited By</label>
          <div class="controls readonly">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</div>
        </div>
      </div>
    </div>  

    @if(count($ledger_details) > 0)
      @include('includes.installment_account_ledger_details.all')
    @endif
  </section>
  <script type="text/javascript">
    var mo_amortization = "<?php echo $ledger->mo_amortization; ?>";
    var reservation = "<?php echo config('constants.PAYMENT_TYPE_RESERVATION_FEE'); ?>";
    var downpayment = "<?php echo config('constants.PAYMENT_TYPE_DOWNPAYMENT'); ?>";
    var ma = "<?php echo config('constants.PAYMENT_TYPE_MA'); ?>";
    var penalty_payment = "<?php echo config('constants.PAYMENT_TYPE_PENALTY_PAYMENT'); ?>";
    var penalty_fee = "<?php echo config('constants.PAYMENT_TYPE_PENALTY_FEE'); ?>";
    var full_payment = "<?php echo config('constants.PAYMENT_TYPE_FULL_PAYMENT'); ?>";
    var bank_finance_payment = "<?php echo config('constants.PAYMENT_TYPE_BANK_FINANCE_PAYMENT'); ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/add_installment_account_ledger_detail.js') }}"></script>
@stop