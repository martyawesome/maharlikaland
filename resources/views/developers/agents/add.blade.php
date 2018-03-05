@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_attendances_from_excel')
  <section class="content-header">
    <h1>
      Add Agent
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Agents</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
      <!-- @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN')
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        <div class="box box-primary">
          <div class="box-body">
            <input type="button" class="btn btn-success" id="import_excel_button" value="Import from Excel" style="margin-right:5px;"></input>
          </div>
        </div>
      @endif -->
      <h4><i>Search Agent</i></h4>
      <div class="box box-primary">
        <div class="box-body">
          <div class="form-group">
            {!! Form::label('first_name', 'First Name'); !!}
            {!! Form::text('first_name', "", ['class' => 'form-control']) !!}
          </div>
          <div class="form-group">
            {!! Form::label('last_name', 'Last Name'); !!}
            {!! Form::text('last_name', "", ['class' => 'form-control']) !!}
          </div>
          <div class="form-group">
            {!! Form::label('email', 'Email'); !!}
            {!! Form::text('email', "", ['class' => 'form-control']) !!}
          </div>
        </div>
      </div>
      <div class="box box-primary" id="main_agents_container">
        <div class="box-body" id="agents_container">
        </div>
      </div>
  </section>
  <div id="confirmation-modal" class="modal modal-primary fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to add this agent?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-confirmation-button">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="sure-confirmation-button">Add</button>
        </div>
      </div>
    </div>
  </div>
  <div id="security-code-modal" class="modal modal-warning fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Security</h4>
        </div>
          <div class="modal-body">
            {!! Form::model(null) !!}
            <div class="form-group">
              {!! Form::label('security_code', 'Security Code*'); !!}
              {!! Form::password('security_code', ['class'=>'form-control', 'id' => 'security_code']) !!}
            </div>      
            {!! Form::close() !!}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="close-security-code-button">Close</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-security-code-button">Submit</button>
          </div>
      </div>
    </div>
  </div>
  <div id="invalid-modal" class="modal modal-danger fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Warning!</h4>
        </div>
        <div class="modal-body">
          <p id="invalid-modal-message">Invalid security code</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div id="success-modal" class="modal modal-success fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success!</h4>
        </div>
        <div class="modal-body">
          <p>The agent was successfully added</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="{{ URL::asset('js/search_agent.js') }}"></script>
@stop