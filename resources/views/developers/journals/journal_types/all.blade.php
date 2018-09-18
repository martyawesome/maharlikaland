@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Journal Types
    </h1>
    <ol class="breadcrumb">
      <li>Journals</li>
      <li class="active">Journals Types</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
        <div class="box-body">
          <a href="{{ route('add_journal_type') }}" class="btn btn-success" style="margin-right: 5px;">Add</a>
        </div>
      </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($journal_types) > 0)
              <table id="objects" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($journal_types as $journal_type)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_journal_type', $journal_type->id) }}">
                      <td>
                        {{ $journal_type->type }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No journal types found
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