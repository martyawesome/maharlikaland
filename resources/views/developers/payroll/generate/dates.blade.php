@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Payroll
    </h1>
    <ol class="breadcrumb">
      <li>Payroll</li>
      <li class="active">Generate</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model(null) !!}
      <div class="box box-primary">
        <div class="box-body">
          <div class="form-group{{ $errors->has('date_range') ? ' has-error' : '' }}">
            {!! Form::label('date_range', 'Date range*'); !!}
            {!! Form::text('date_range', null, ['class' => 'form-control pull-right']) !!}
            {!! $errors->first('date_range', '<span class="help-block">:message</span>') !!}
          </div>
      </div>
      <div class="box-footer">
        {!! Form::submit('Generate', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    $(function(){
      $('#date_range').daterangepicker();
    });
  </script>
@stop


