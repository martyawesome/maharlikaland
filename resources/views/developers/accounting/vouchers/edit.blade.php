@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit Voucher
      <small><b><a href="{{ URL::route('voucher', array($project->slug,$voucher->voucher_number)) }}">Voucher {{ $voucher->voucher_number }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li>Account Titles</li>
      <li>Vouchers</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($voucher) !!}
      @include('includes.accounting.vouchers.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop