@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Account Titles
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li class="active">Account Titles</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    <div class="list-group">
      <div class="box-footer" style="margin-bottom: 15px;">
        {!! link_to_route('add_account_title', 'Add', array(), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      </div>
      <div class="box box-default">
        <div class="box-body">
          @if(count($account_titles) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                @foreach($account_titles as $account_title)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_account_title', $account_title->slug) }}">
                    <td>
                      {{ $account_title->title }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <p>No account titles found</p>
          @endif
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