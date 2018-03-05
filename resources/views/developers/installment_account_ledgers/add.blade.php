@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      New Ledger Acount
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">New</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($ledger) !!}
      @include('includes.installment_account_ledger.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_installment_account_ledger.js') }}"></script>
@stop