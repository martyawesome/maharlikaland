@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Edit User
      <small>Users</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Users</li>
      <li class="active">Edit User</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($user, array('files' => true)) !!}
      @if($user->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
        @include('includes.developers.add_edit')
        @include('includes.users.add_edit_user_type')
      @else
        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
          @include('includes.users.add_edit_user_type')
        @else
          @include('includes.users.add_edit')
        @endif
      @endif
      
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop