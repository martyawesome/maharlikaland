@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_ledger_from_excel')
  @include('modals.developers.delete_installment_account_ledger')
  <section class="content-header">
    <h1>
      Ledger
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.installment_account_ledger.view')
    <div class="box-footer" style="margin-bottom: 15px; ">
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        {!! link_to_route('edit_ledger', 'Edit', array($buyer->id, $ledger->id), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
        <input type="button" class="btn btn-success" id="import_ledger_button" value="Import from Excel" style="margin-right:5px;"></input>
        @if($payment_types != null)
          {!! link_to_route('add_ledger_entry', 'Add Entry', array($buyer->id, $ledger->id), ['class' => 'btn btn-warning', 'style' => 'margin-right:5px;']) !!}
        @endif
      @endif

      {!! link_to_route('export_ledger_to_excel', 'Export to Excel', array($buyer->id, $ledger->id), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      

      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      @endif

      <!-- {!! link_to_route('export_ledger_to_pdf', 'Export to PDF', array($buyer->id, $ledger->id), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}-->
    </div>
    @if(count($ledger_details) > 0)
      @include('includes.installment_account_ledger_details.all')
    @endif
  </section>
@stop