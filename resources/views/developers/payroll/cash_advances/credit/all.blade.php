@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_ca_from_excel')
  <section class="content-header">
    <h1>
      Cash Advance Credit
      <small><a href="{{ URL::route('cash_advances') }}">Cash Advances</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Cash Advances</li>
      <li class="active">Credit</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('add_cash_advance_credit') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
        <input type="button" class="btn btn-success" id="import_excel_button" value="Import from Excel" style="margin-right:5px;"></input>
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
                    <th>Date</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cash_advances as $cash_advance)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_cash_advance_credit', array($cash_advance->id)) }}">
                      <td>
                        {{ $cash_advance->last_name }}, {{ $cash_advance->first_name }} {{ $cash_advance->middle_name }} 
                      </td>
                      <td>
                        {{ $cash_advance->user_type }}
                      </td>
                      <td>
                        {{ $cash_advance->date }}
                      </td>
                      <td>
                        {{ number_format($cash_advance->amount, 2, '.', ',') }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No cash advances credit found
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