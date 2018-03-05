@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.developers.import_salary_rates_from_excel')
  <section class="content-header">
    <h1>
      Salary Rates
    </h1>
    <ol class="breadcrumb">
      <li class="active">Salary Rates</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('add_salary_rate') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
        <input type="button" class="btn btn-success" id="import_button" value="Import from Excel"></input>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($salary_rates) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Amount (<?php echo config('constants.CURRENCY'); ?>)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($salary_rates as $salary_rate)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_salary_rate', array($salary_rate->id)) }}">
                      <td>
                        {{ $salary_rate->last_name }}, {{ $salary_rate->first_name }} {{ $salary_rate->middle_name }} 
                      </td>
                      <td>
                        {{ $salary_rate->rate }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No salary rates found
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