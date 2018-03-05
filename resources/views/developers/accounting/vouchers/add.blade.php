@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New Voucher
      <small><b><a href="{{ URL::route('vouchers', array($project->slug)) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li>Account Titles</li>
      <li>Vouchers</li>
      <li class="active">New</li>
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