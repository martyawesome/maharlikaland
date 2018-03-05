@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Vouchers
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li class="active">Vouchers</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($vouchers) > 0)
            <table id="vouchers" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Voucher Number</th>
                  <th>Payee</th>
                  <th>Amount (<?php echo config('constants.CURRENCY'); ?>)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vouchers as $voucher)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('voucher',array($voucher->voucher_number)) }}">  
                    <td>{{ $voucher->date }}</td>
                    <td>{{ $voucher->voucher_number }}</td>
                    <td>{{ $voucher->payee }}</td>
                    <td>{{ number_format($voucher->details_amount, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No vouchers found
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer">
      {!! link_to_route('add_voucher', 'Add', null, ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    </div>
  </section>
  <script>
      $(function () {
        $('#vouchers').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
        });
      });
      $(".clickable-row").click(function() {
          window.document.location = $(this).data("href");
      });
    </script>
@stop