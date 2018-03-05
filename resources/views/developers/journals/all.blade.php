@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Journals
    </h1>
    <ol class="breadcrumb">
      <li class="active">Journals</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <a href="{{ route('add_journal') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($journals) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
                      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
                        <th>User</th>
                    @endif
                    <th>Date</th>
                    <th>Type</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($journals as $journal)
                    @if($journal->user_id == Auth::user()->id)
                      <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_journal', array($date, $journal->id)) }}">
                    @else
                      <tr>
                    @endif
                      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
                      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
                        <td>
                          {{ $journal->user_name }}
                        </td>
                      @endif
                      <td>
                        {{ $journal->date }}
                      </td>
                      <td>
                        {{ $journal->type }}
                      </td>
                      <td>
                        {{ $journal->entry }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No journals found
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