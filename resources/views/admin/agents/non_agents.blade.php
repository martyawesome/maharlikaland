@extends('admin.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      All Non-Brokers
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Brokers</li>
      <li class="active">Non-Brokers</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('admin_create_account_agent', 0) }}" class="btn btn-success" style="margin-right: 5px;">New Account</a>
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
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('admin_create_account_agent',$user->id) }}">
                      <td><img class="table-profile-pic" src="<?php echo asset("").$user->profile_picture_path?>" alt="User profile picture"></td>
                      <td>
                        <b>{{ $user->first_name }} {{ $user->last_name }}</b></br>
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
