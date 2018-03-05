@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New Buyer
    </h1>
    <ol class="breadcrumb">
      <li>Buyers</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($buyer) !!}
      @include('includes.buyers.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop