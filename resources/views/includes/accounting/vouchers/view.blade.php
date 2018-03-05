<div>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Date</label>
        <div class="controls readonly">{{ $voucher->date }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Voucher Number</label>
        <div class="controls readonly">{{ $voucher->voucher_number }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Payee</label>
        <div class="controls readonly">{{ $voucher->payee }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Issued By</label>
        <div class="controls readonly">{{ $voucher->issued_by }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Received By</label>
        <div class="controls readonly">{{ $voucher->received_by }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Amount</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($voucher_amount, 2, '.', ',') }}</div>
      </div>
    </div>
  </div> 
</div>