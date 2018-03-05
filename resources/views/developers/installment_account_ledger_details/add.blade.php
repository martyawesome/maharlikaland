@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      New Ledger Entry
      <small><a href="{{ URL::route('ledger', [$buyer->id, $property->slug]) }}">Back to Ledger</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($ledger_detail, array('style'=>'margin-bottom:20px')) !!}
      @include('includes.installment_account_ledger_details.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
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