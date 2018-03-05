@extends('admin.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      All Developers
      <small>Developers</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Developers</li>
      <li class="active">All Developers</li>
    </ol>
  </section>
  <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              @if(count($developers) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Developer</th>
                    <th>Information</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($developers as $developer)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('admin_edit_developer',$developer->id) }}">
                      <td><img class="table-profile-pic" src="<?php echo asset("").$developer->logo_path ?>" alt="Developer profile picture"></td>
                      <td>
                        <b>{{ $developer->name }}</b></br>
                        @if($developer->address)
                          {{ $developer->address }}</br>
                        @endif
                        {{ $developer->contact_number }}</br>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                No developers found
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