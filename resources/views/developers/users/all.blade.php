@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_users_from_excel')
  <section class="content-header">
    <h1>
      All Users
      <small>Users</small>
    </h1>
    <ol class="breadcrumb">
      <li class="active">Users</li>
    </ol>
  </section>
  <section class="content">    
    <div class="box">
      <div class="box-body">
        <input type="button" class="btn btn-success" id="import_button" value="Import from Excel"></input>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($users) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Profile Pic</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('user',$user->username) }}">
                    <td><img class="table-profile-pic" src="<?php echo asset("").$user->profile_picture_path?>" alt="User profile picture"></td>
                    <td>
                      <b>
                        {{ $user->first_name }}
                        @if($user->middle_name)
                          {{ $user->middle_name }}
                        @endif
                        {{ $user->last_name }}
                      </b>
                      </br>
                      {{ $user->user_type }}
                      </br>
                      @if($user->address)
                        {{ $user->address }}
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No users found
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
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
      $(".clickable-row").click(function() {
          window.document.location = $(this).data("href");
      });
    </script>
@stop
