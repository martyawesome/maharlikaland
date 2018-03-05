@extends('developers.base_dashboard')
@section('content')
  @include('modals.developers.delete_buyer')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <ol class="breadcrumb">
      <li>Buyers</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.buyers.view')
      <div class="box-footer">
        {!! link_to_route('edit_buyer', 'Edit', array($buyer->id), ['class' => 'btn btn-primary']) !!}
        @if(!$user)
          {!! link_to_route('create_buyer_user_account', 'Create User Account', array($buyer->id), ['class' => 'btn btn-success']) !!}
        @endif
        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
          <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
        @endif
      </div>
  </section>
@stop