@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      My Account
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Users</li>
      <li class="active">View</li>
    </ol>
  </section>
  
  @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
  or Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN'))
    <section class="content">
      @include('includes.developers.view')
    </section>
  @endif

  <section class="content">
    @include('includes.users.view') 
  </section>

  @if($agent)
    <section class="content" style="clear:both;">
        @include('includes.agents.view')
    </section>
  @endif

  <section class="content">  
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      {!! link_to_route('edit_admin_account', 'Edit', array($user->username), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    @else
      {!! link_to_route('edit_account', 'Edit', array($user->username), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    @endif

    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') or 
    Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      @if($agent)
        {!! link_to_route('user_edit_broker', 'Edit Broker', array($user->username, $agent->prc_license_number), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      @else
        {!! link_to_route('user_new_broker', 'New Broker', array($user->username), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      @endif
    @endif
  </section>
@stop