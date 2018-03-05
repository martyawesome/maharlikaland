@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New Particular
      <small><b><a href="{{ URL::route('voucher', array($project->slug, $voucher->voucher_number)) }}">Voucher Number: {{ $voucher->voucher_number }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li>Account Titles</li>
      <li>Vouchers</li>
      <li>Particulars</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($voucher_detail) !!}
      @include('includes.accounting.vouchers.add_edit_detail')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop