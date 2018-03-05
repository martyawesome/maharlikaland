@extends('admin.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Edit Developer
      <small>Developers</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Developers</li>
      <li class="active">All Developers</li>
      <li class="active">Edit Developer</li>
      <li class="active">{{ $developer->name }}</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('admin_developer_accounts', $developer->id) }}" class="btn btn-primary" style="margin-right: 5px;">Admin Accounts</a>
      </div>
    </div>
    {!! Form::model($developer, array('files' => true)) !!}
      @include('includes.developers.add_edit')
      <div class="box-footer">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary'])!!}
        <!-- <div id="deleteAccountButton" class="btn btn-danger">Delete</div> -->
      </div>
    {!! Form::close() !!}
  </section>
@stop