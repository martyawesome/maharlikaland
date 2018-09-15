<div>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Date Covered</label>
        <div class="controls readonly">{{ $electricity_bill->date_covered }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Consumption</label>
        <div class="controls readonly">{{ number_format($electricity_bill->consumption, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Bill</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($electricity_bill->bill, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Computed Consumtion</label>
        <div class="controls readonly">{{ number_format($electricity_bill->details_consumption, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Computed Bill</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($electricity_bill->details_bill, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Payment Collected</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($electricity_bill->details_payment, 2, '.', ',') }}</div>
      </div>
      @if($electricity_bill->remarks)
        <div class="control-group">
          <label class="control-label">Remarks</label>
          <div class="controls readonly">{{ $electricity_bill->remarks }}</div>
        </div>
      @endif
    </div>
  </div> 
</div>