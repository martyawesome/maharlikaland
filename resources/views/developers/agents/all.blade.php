@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      All Agents
    </h1>
    <ol class="breadcrumb">
      <li class="active"><i class="fa fa-user" style="margin-right:5px;"></i>Agents</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($agents) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                @foreach($agents as $agent)
                  <tr id="{{$agent->id}}" class='clickable-row developer-agent-item' style="cursor: pointer;">
                    <td>
                      <b>{{ $agent->first_name }} {{ $agent->last_name }}</b>
                      <p>{{ $agent->user_type }}</p>
                    </td>
                    <td>
                      {{ $agent->address }} </br>
                      {{ $agent->contact_number }} </br>
                      {{ $agent->email }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No agents found
            @endif
          </div>
        </div>
      </div>
    </div>
      <!-- <div class="box box-primary" id="main_agents_container">
        <div class="box-body" id="agents_container">
          @if(count($agents) > 0)
            @foreach($agents as $agent)
              @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
                <div id="{{$agent->id}}"  class='list-group-item list-group-item-action developer-agent-item' onmouseover="this.style.background='tomato';" onmouseout="this.style.background='white';">
              @else
                <div id="{{$agent->id}}"  class='list-group-item list-group-item-action developer-agent-item'>
              @endif

                <h5 class='list-group-item-heading'> {{ $agent->first_name }} {{ $agent->last_name }}</h5>
                <p class='list-group-item-text'>
                  {{ $agent->user_type }}</br>
                  {{ $agent->address }}</br>
                  {{ $agent->contact_number }}</br>
                  {{ $agent->email }}</p>
              </div>
            @endforeach
          @else
            No agents found
          @endif
        </div>
      </div> -->
  </section>
  <div id="confirmation-modal" class="modal modal-danger fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to remove this agent?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-confirmation-button">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="sure-confirmation-button">Remove</button>
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
  <script>
      $(function () {
        $('#objects').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
        });
      });
    </script>
  @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN')
  or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
    or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
    <script type="text/javascript" src="{{ URL::asset('js/remove_developers_agent.js') }}"></script>
  @endif
@stop