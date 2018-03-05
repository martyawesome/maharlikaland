@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_attendances_from_excel')
  <section class="content-header">
    <h1>
      Attendance for {{ $formatted_date }}
      <small><a href="{{ URL::route('attendance') }}">Back to Calendar</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Attendance</li>
      <li>Calendar</li>
      <li class="active">{{ $formatted_date }}</li>
    </ol>
  </section>
  <section class="content">
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
    or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
      <div class="box box-primary">
        <div class="box-body">
          <a href="{{ route('add_attendance', array($date)) }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
          <input type="button" class="btn btn-success" id="import_excel_button" value="Import from Excel" style="margin-right:5px;"></input>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($attendances) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Time-in</th>
                  <th>Time-out</th>
                  <th>Hours</th>
                </tr>
              </thead>
              <tbody>
                @foreach($attendances as $attendance)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_attendance',array($date, $attendance->id)) }}">
                    <td>
                      {{ $attendance->first_name }} {{ $attendance->last_name }}
                    </td>
                    <td>
                        {{ $attendance->time_in }}
                    </td>
                    <td>
                        {{ $attendance->time_out }}
                    </td>
                    <td>
                        {{ $attendance->hours }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No attendances found
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(function () {
      $('#objects').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true
      });
    });
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
  </script>
@stop


