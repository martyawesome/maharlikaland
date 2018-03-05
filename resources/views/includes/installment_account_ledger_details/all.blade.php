<div class="box box-default">
  <div class="box-body" style="overflow: auto;">
    <table id="example2"  class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>PAYMENT DATE</th>
          <th>DETAILS OF PAYMENT</th>
          <th>MONTH COVERED</th>
          <th>OR NO</th>
          <th>AMOUNT (<?php echo config('constants.CURRENCY'); ?>)</th>
          <th>INTEREST (<?php echo config('constants.CURRENCY'); ?>)</th>
          <th>PRINCIPAL (<?php echo config('constants.CURRENCY'); ?>)</th>
          <th>BALANCE (<?php echo config('constants.CURRENCY'); ?>)</th>
          <th>PENALTY (<?php echo config('constants.CURRENCY'); ?>)</th>
          <th>REMARKS</th>
        </tr>
      </thead>
      <tbody>
      @foreach($ledger_details as $ledger_detail)
        <tr>
          <td>
            @if($ledger_detail->payment_date != "0000-00-00")
              {{ $ledger_detail->payment_date }}
            @endif
          </td>
          <td>{{ $ledger_detail->details_of_payment }}</td>
          <td>{{ $ledger_detail->ma_covered_date }}</td>
          <td>{{ $ledger_detail->or_no }}</td>
          <td>{{ number_format($ledger_detail->amount_paid, 2, '.', ',') }}</td>
          <td>{{ number_format($ledger_detail->interest, 2, '.', ',') }}</td>
          <td>{{ number_format($ledger_detail->principal, 2, '.', ',') }}</td>
          <td>{{ number_format($ledger_detail->balance, 2, '.', ',') }}</td>
          <td>{{ number_format($ledger_detail->penalty, 2, '.', ',') }}</td>
          <td>{{ $ledger_detail->remarks }}</td>
          <td>
            @if($ledger_detail->payment_type_id != config('constants.PAYMENT_TYPE_PENALTY_FEE'))
              {!! link_to_route('edit_ledger_entry', 'Edit', array($buyer->id, $ledger->id, $ledger_detail->id), ['class' => 'btn btn-success']) !!}
            @endif
          </td>
          <!--<td>{!! link_to_route('delete_ledger_entry', 'Delete', array($buyer->id, $ledger->id, $ledger_detail->id), ['class' => 'btn btn-danger']) !!}</td> -->
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>