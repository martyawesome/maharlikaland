@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Attendance for {{ $formatted_date }}
      <small><a href="{{ URL::route('attendance') }}">Back to Calendar</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Attendance</li>
      <li>Calendar</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($attendance) !!}
      <div class="box box-primary">
        <div class="box-body">
          <div class="form-group">
            {!! Form::label('user', 'User'); !!}
            {!! Form::select('user', $users, $attendance->user_id, ['class' => 'form-control']) !!}
          </div>
          <!-- Time in-->
          <div class="bootstrap-timepicker">
            <div class="form-group">
              <label>Time-in:</label>
              <div class="input-group">
                <input type="text" id="time-in" name="time-in" class="form-control timepicker" value="<?php echo $attendance->time_in; ?>">
                <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- Time out-->
          <div class="bootstrap-timepicker">
            <div class="form-group">
              <label>Time-out:</label>
              <div class="input-group">
                <input type="text" id="time-out" name="time-out" class="form-control timepicker" value="<?php echo $attendance->time_out; ?>">
                <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
      <script type="text/javascript">
        $(".timepicker").timepicker({
          showInputs: false
        });
      </script>
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right: 5px;'])!!}
        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
        or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
          <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
        @endif
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var date = "<?php echo $date; ?>";
    var attendance = "<?php echo $attendance->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_attendance.js') }}"></script>
@stop


