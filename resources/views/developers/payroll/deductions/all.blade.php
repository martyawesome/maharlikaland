@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_payroll_deductions_from_excel')
  <section class="content-header">
    <h1>
      Payroll Deductions
    </h1>
    <ol class="breadcrumb">
      <li>Payroll</li>
      <li class="active">Deductions</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('add_payroll_deduction') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
        <input type="button" class="btn btn-success" id="import_excel_button" value="Import from Excel" style="margin-right:5px;"></input>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($deductions) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Amount (<?php echo config('constants.CURRENCY'); ?>)</th>
                    <th>Type</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($deductions as $deduction)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_payroll_deduction', array($deduction->id)) }}">
                      <td>
                        {{ $deduction->last_name }}, {{ $deduction->first_name }} {{ $deduction->middle_name }} 
                      </td>
                      <td>
                        <?php
                          $formatted_date = new DateTime($deduction->date);
                          $formatted_date = $formatted_date->format('F j, Y');  
                          echo $formatted_date;
                         ?>
                      </td>
                      <td>
                        {{ $deduction->amount }}
                      </td>
                      <td>
                        {{ $deduction->type }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No deductions found
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
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
  </script>
@stop