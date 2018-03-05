<div class="box box-primary">
  <div class="box-body">
    <div class="control-group">
      <label class="control-label">Name</label>
      <div class="controls readonly">{{ $buyer->last_name }}, {{ $buyer->first_name }} {{ $buyer->middle_name }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Property</label>
      <div class="controls readonly">{{ $property->name }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Home Address</label>
      <div class="controls readonly">{{ $buyer->home_address }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Contact Number (Mobile)</label>
      <div class="controls readonly">{{ $buyer->contact_number_mobile }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Email</label>
      <div class="controls readonly">{{ $buyer->email }}</div>
    </div>
  </div>
</div>  
<div class="box box-success">
  <div class="box-body">
    <div class="control-group">
      <label class="control-label">Total Contract Price (TCP)</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->tcp, 2, '.', ',') }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Years to Pay</label>
      <div class="controls readonly">{{ $ledger->years_to_pay }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Reservation Fee</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->reservation_fee, 2, '.', ',') }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Downpayment (DP)</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->dp, 2, '.', ',') }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Downpayment (DP) Percentage</label>
      <div class="controls readonly">{{ $ledger->dp_percentage }}%</div>
    </div>
    <div class="control-group">
      <label class="control-label">Downpayment (DP) Discount</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->dp_discount, 2, '.', ',') }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Due Date</label>
      <div class="controls readonly">{{ $ledger->due_date }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Monthly Interest</label>
      <div class="controls readonly">{{ $ledger->mo_interest }}%</div>
    </div>
    <div class="control-group">
      <label class="control-label">Monthly Amortization</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->mo_amortization, 2, '.', ',') }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Balance</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->balance, 2, '.', ',') }}</div>
    </div>
    @if($ledger->tct_no)
      <div class="control-group">
        <label class="control-label">TCT Number</label>
        <div class="controls readonly">{{ $ledger->tct_no }}</div>
      </div>
    @endif
    @if($ledger->contract_date and $ledger->contract_date != "0000-00-00")
      <div class="control-group">
        <label class="control-label">Contract Date</label>
        <div class="controls readonly">{{ $ledger->contract_date }}</div>
      </div>
    @endif
    <div class="control-group">
      <label class="control-label">Unpaid Penalty</label>
      <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($remaining_penalty, 2, '.', ',') }}</div>
    </div>
  </div>
</div>
@if($ledger->bank_finance)
  <div class="box box-primary"> 
    <div class="box-body">
      <div class="control-group">
        <label class="control-label">Bank Finance Loan</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->bank_finance, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Loan-Balance Difference</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->bank_finance_diff, 2, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Difference Payable in Months</label>
        <div class="controls readonly">{{ number_format($ledger->bank_finance_months, 0, '.', ',') }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Monthly Interest</label>
        <div class="controls readonly">{{ number_format($ledger->bank_finance_mo_interest*100, 2, '.', ',') }}%</div>
      </div>
      <div class="control-group">
        <label class="control-label">Monthly Payment</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($ledger->bank_finance_monthly, 2, '.', ',') }}</div>
      </div>
    </div>
  </div> 
@endif