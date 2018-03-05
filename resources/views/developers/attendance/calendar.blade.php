@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Attendance Calendar
    </h1>
    <ol class="breadcrumb">
      <li>Attendance</li>
      <li class="active">Calendar</li>
    </ol>
  </section>
  <section class="content">
    <div class="col-lg-10">
  	  <div class="box box-primary">
  	    <div class="box-body no-padding">
  	      <div id="calendar"></div>
  	    </div>
  	  </div>
  	</div>
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/attendance_calendar.js') }}"></script>
@stop


