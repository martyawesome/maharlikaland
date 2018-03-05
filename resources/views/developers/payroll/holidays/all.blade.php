@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Holidays
    </h1>
    <ol class="breadcrumb">
      <li>Payroll</li>
      <li class="active">Holidays</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('add_holiday') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
        <input type="button" class="btn btn-primary" value="Sync from Google" id="sync-button">
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($holidays) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Type</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($holidays as $holiday)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_holiday', array($holiday->id)) }}">
                      <td>
                        {{ $holiday->date }} 
                      </td>
                      <td>
                        {{ $holiday->name }}
                      </td>
                      <td>
                        @if($holiday->type == config('constants.HOLIDAY_REGULAR'))
                          Regular Working
                        @else
                          Special Non-working
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No holidays found
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
  @include('modals.sync_holidays')
  <script type="text/javascript" src="{{ URL::asset('js/sync_holidays.js') }}"></script>
@stop