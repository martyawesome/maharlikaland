@extends('developers.base_dashboard')
@section('content')
@include('modals.danger')
  <section class="content-header">
    <h1>
      New Prospect Buyer
    </h1>
    <ol class="breadcrumb">
      <li>Prospect Buyers</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($prospect_buyer) !!}
      @include('includes.prospect_buyers.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop