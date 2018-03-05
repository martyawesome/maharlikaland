@extends('developers.base_dashboard')
@section('content')
  @include('modals.developers.delete_account_title')
  <section class="content-header">
    <h1>
      Edit Account Title
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li>Account Titles</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($account_title) !!}
      <div class="box box-primary">
        <div class="box-body">
          <div class="form-group{{ $errors->has('account_title') ? ' has-error' : '' }}">
            {!! Form::label('account_title', 'Account Title*'); !!}
            {!! Form::text('account_title', $account_title->title, array('type' => 'text', 'class' => 'form-control')) !!}
            {!! $errors->first('account_title', '<span class="help-block">:message</span>') !!}
          </div>
        </div> 
      </div>
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right: 5px;'])!!}
        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
        or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
          <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
        @endif
      </div>
    {!! Form::close() !!}
  </section>
@stop