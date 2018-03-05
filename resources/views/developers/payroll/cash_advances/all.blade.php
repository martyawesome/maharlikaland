@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Cash Advances
    </h1>
    <ol class="breadcrumb">
      <li class="active">Cash Advances</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('cash_advances_credit') }}" class="btn btn-success" style="margin-right: 5px;">Credit</a>
        <a href="{{ route('cash_advances_payments') }}" class="btn btn-success" style="margin-right: 5px;">Payments</a>
        </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($cash_advances) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Remaining Amount (<?php echo config('constants.CURRENCY'); ?>)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cash_advances as $cash_advance)
                    <tr>
                      <td>
                        {{ $cash_advance->last_name }}, {{ $cash_advance->first_name }} {{ $cash_advance->middle_name }} 
                      </td>
                      <td>
                        {{ $cash_advance->user_type }}
                      </td>
                      <td>
                        {{ number_format($cash_advance->amount, 2, '.', ',') }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No cash advances found
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(function () {
      $('#objects').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true
      });
    });
  </script>
@stop