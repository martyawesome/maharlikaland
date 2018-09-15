@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_bills_from_excel')
  <section class="content-header">
    <h1>
      {{ $project->name }}
      <small>Electricity Bills</small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li class="active">Electricity</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($electricity_bills) > 0)
            <table id="bills" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date Covered</th>
                  <th>Consumption</th>
                  <th>Bill (<?php echo config('constants.CURRENCY'); ?>)</th>
                  <th>Computed Consumption</th>
                  <th>Computed Bill (<?php echo config('constants.CURRENCY'); ?>)</th>
                  <th>Payment Collected (<?php echo config('constants.CURRENCY'); ?>)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($electricity_bills as $electricity_bill)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('view_bill_electricity_project',array($project->slug,$electricity_bill->date_covered)) }}">
                    <td>{{ $electricity_bill->date_covered }}</td>
                    <td>{{ number_format($electricity_bill->consumption, 2, '.', ',') }}</td>
                    <td>{{ number_format($electricity_bill->bill, 2, '.', ',') }}</td>
                    <td>{{ number_format($electricity_bill->details_consumption, 2, '.', ',') }}</td>
                    <td>{{ number_format($electricity_bill->details_bill, 2, '.', ',') }}</td>
                    <td>{{ number_format($electricity_bill->details_payment, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No electricity bills found
            @endif
          </div>
        </div>
      </div>
    </div>
    Unpaid Bills
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($unpaid_bills) > 0)
            <table id="unpaid_bills" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Property</th>
                  <th>Amount (<?php echo config('constants.CURRENCY'); ?>)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($unpaid_bills as $unpaid_bill)
                  <tr>
                    <td>{{ $unpaid_bill->buyer }}</td>
                    <td>{{ $unpaid_bill->property }}</td>
                    <td>{{ number_format($unpaid_bill->amount, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No unpaid bills found
            @endif
          </div>
        </div>
      </div>
    </div>
    <div style="margin-bottom:15px;">
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
            or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        {!! link_to_route('add_bill_electricity_project', 'Add', array($project->slug), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
        <input type="button" class="btn btn-success" id="import_button" value="Import Bills from Excel" style="margin-right:5px;"></input>
      @endif
      
      {!! link_to_route('export_project_electricity_bills_to_excel', 'Export Bills to Excel', array($project->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      {!! link_to_route('export_project_electricity_bills_to_pdf', 'Export Bills to PDF', array($project->slug), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
    </div>
  </section>
  <script>
    $(function () {
      $('#bills').DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true
      });
      $('#unpaid_bills').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true
      });
      $(".clickable-row").click(function() {
          window.document.location = $(this).data("href");
      });
    });
  </script>
@stop