@extends('developers.base_dashboard')

@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Dashboard
      <small>{{ $developer->name }}</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-6 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Monthly Amortizations Due Today</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            @if(count($ledgers_due_date_today) > 0)
              <table id="ledgers_due_date_today" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Property</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ledgers_due_date_today as $ledger_due_date_today)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('ledger',array($ledger_due_date_today->buyer_id, $ledger_due_date_today->property_slug)) }}">
                      @if($ledger_due_date_today->property_id != 0 and $ledger_due_date_today->property_id != null)
                        <td>{{ $ledger_due_date_today->property }}</td>
                      @else
                        <td></td>
                      @endif
                      <td>{{ number_format($ledger_due_date_today->mo_amortization, 2, '.', ',') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No ledgers due today
            @endif
          </div>
        </div>
      </section>
      <!-- Right col -->
      <section class="col-lg-6 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Birthdays for <?php echo date("F");?></h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            @if(count($birthdays) > 0)
            <table id="birthdays" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Day</th>
                </tr>
              </thead>
              <tbody>
                @foreach($birthdays as $birthday)
                  <tr>
                    <td>{{ $birthday->last_name }}, {{ $birthday->first_name }}</td>
                    <td>{{ $birthday->user_type }}</td>
                    <td><?php echo date('d', strtotime($birthday->birthdate)); ?></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No birthdays for today
            @endif
          </div>
        </div>
      </section>
    </div>
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Last 10 Vouchers</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            @if(count($voucher_details) > 0)
            <table id="voucher_details" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Account Title</th>
                  <th>Amount</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($voucher_details as $voucher_detail)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_voucher_detail',array($voucher_detail->project, $voucher_detail->voucher_number, $voucher_detail->id)) }}">
                    <td>{{ $voucher_detail->property }}</td>
                    <td>{{ $voucher_detail->account_title }}</td>
                    <td>{{ number_format($voucher_detail->amount, 2, '.', ',') }}</td>
                    <td>{{ $voucher_detail->date }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No vouchers found
            @endif
          </div>
        </div>
      </section>
    </div>
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Last 10 Ledger Entries</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            @if(count($ledger_details) > 0)
            <table id="ledger_details" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Payment Date</th>
                  <th>Payment Type</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ledger_details as $ledger_detail)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_ledger_entry',array($ledger_detail->buyer_id, $ledger_detail->installment_account_ledger_id, $ledger_detail->id)) }}">
                    <td>{{ $ledger_detail->property }}</td>
                    <td>{{ $ledger_detail->payment_date }}</td>
                    <td>{{ $ledger_detail->payment_type }}</td>
                    <td>{{ number_format($ledger_detail->amount_paid, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No ledger entries found
            @endif
          </div>
        </div>
      </section>
    </div>
  </section>
  <script>
      $(function () {
        $('#ledgers_due_date_today').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": false,
          "info": true,
          "autoWidth": true
        });
      });
      $(".clickable-row").click(function() {
          window.document.location = $(this).data("href");
      });
    </script>
@stop