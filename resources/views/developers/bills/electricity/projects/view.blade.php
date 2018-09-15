@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_bills_from_excel')
  @include('modals.developers.delete_project_electricity_bills')
  <section class="content-header">
    <h1>
      Electricity Bill
      <small><b><a href="{{ URL::route('bills_electricity_project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Water</li>
      <li class="active">{{ $project->name }} </li>
    </ol>
  </section>
  <section class="content">
    @include('includes.bills.electricity.view')
    <div class="box-footer" style="margin-bottom: 15px;">
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        {!! link_to_route('edit_bill_electricity_project', 'Edit', array($project->slug, $electricity_bill->date_covered), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
        <input type="button" class="btn btn-success" id="import_button" value="Import Bills from Excel" style="margin-right:5px;"></input>
      @endif

      {!! link_to_route('export_monthly_electricity_bills_to_excel', 'Export Bills to Excel', array($project->slug,$electricity_bill->date_covered), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      {!! link_to_route('export_monthly_electricity_bills_to_pdf', 'Export Bills to PDF', array($project->slug,$electricity_bill->date_covered), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
    
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      @endif
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($electricity_bill_details) > 0)
            <table id="bills" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Property</th>
                  <th>Consumption</th>
                  <th>Bill</th>
                  <th>Payment (<?php echo config('constants.CURRENCY'); ?>)</th>
                  <th>Payment Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($electricity_bill_details as $electricity_bill_detail)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_bill_electricity_monthly',array($project->slug,$electricity_bill_detail->property_slug,$electricity_bill->date_covered)) }}">
                    <td>{{ $electricity_bill_detail->property }} ({{ $electricity_bill_detail->last_name }} , {{ $electricity_bill_detail->first_name }})</td>
                    <td>{{ number_format($electricity_bill_detail->consumption, 2, '.', ',') }}</td>
                    <td>{{ number_format($electricity_bill_detail->bill, 2, '.', ',') }}</td>
                    <td>
                      @if($electricity_bill_detail->payment != 0)
                        {{ number_format($electricity_bill_detail->payment, 2, '.', ',') }}
                      @endif
                    </td>
                    <td>
                      @if($electricity_bill_detail->date_payment != "0000-00-00")
                        {{ $electricity_bill_detail->date_payment }}
                      @endif
                    </td>
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
  </section>
  <script>
    $(function () {
      $('#bills').DataTable({
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