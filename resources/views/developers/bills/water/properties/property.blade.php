@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_bills_from_excel')
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small>{{ $project->name }}</small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li>Water</li>
      <li>{{ $project->name }}</li>
      <li class="active">{{ $property->name }}</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Water Bills</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            @if(count($water_bills) > 0)
            <table id="bills" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date Covered</th>
                  <th>Consumption</th>
                  <th>Bill (<?php echo config('constants.CURRENCY'); ?>)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($water_bills as $water_bill)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('view_bill_water_project',array($project->slug,$water_bill->date_covered)) }}">
                    <td>{{ $water_bill->date_covered }}</td>
                    <td>{{ number_format($water_bill->consumption, 2, '.', ',') }}</td>
                    <td>{{ number_format($water_bill->bill, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No water bills found
            @endif
          </div>
        </div>
      </div>
    </div>
    <div style="margin-bottom:15px;">
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
            or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        {!! link_to_route('add_bill_water_property', 'Add', array($project->slug,$property->slug), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
        <input type="button" class="btn btn-success" id="import_button" value="Import Bills from Excel" style="margin-right:5px;"></input>
      @endif
      
      {!! link_to_route('export_project_water_bills_to_excel', 'Export Bills to Excel', array($project->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      {!! link_to_route('export_project_water_bills_to_pdf', 'Export Bills to PDF', array($project->slug), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
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
      });
    </script>
@stop