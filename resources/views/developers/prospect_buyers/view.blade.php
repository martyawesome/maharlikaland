@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.delete_prospect_buyer')
  <section class="content-header">
    <ol class="breadcrumb">
      <li>Prospect Buyers</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.prospect_buyers.view')
      <div class="box-footer">
        {!! link_to_route('edit_prospect_buyer', 'Edit', array($prospect_buyer->id), ['class' => 'btn btn-primary','style' => 'margin-right:5px;']) !!}
        {!! link_to_route('upgrade_prospect_buyer', 'Upgrade to Buyer', array($prospect_buyer->id), ['class' => 'btn btn-success']) !!}

        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
          <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
        @endif
      </div>
  </section>
@stop