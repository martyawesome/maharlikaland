@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.delete_user')
  <section class="content-header">
    <h1>
      View User
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Users</li>
      <li class="active">View</li>
    </ol>
  </section>
  <section class="content">
    @if($agent)
      @include('includes.users.view_broker')
    @else
      @include('includes.users.view')
    @endif
    
    {!! link_to_route('edit_account', 'Edit', array($user->id), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    
    @if($agent)
      {!! link_to_route('user_edit_broker', 'Edit Broker', array($user->email, $agent->prc_license_number), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
    @else
      {!! link_to_route('user_new_broker', 'New Broker', array($user->id), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
    @endif

    @if(Auth::user()->user_type_id == (config('constants.USER_TYPE_DEVELOPER_ADMIN') or config('constants.USER_TYPE_ADMIN')))
      <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
    @endif

  </section>
@stop