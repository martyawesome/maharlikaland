@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.import_buyers_from_excel')
  <section class="content-header">
    <h1>
      Buyers
    </h1>
    <ol class="breadcrumb">
      <li>Buyers</li>
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
            @if(count($buyers) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                @foreach($buyers as $buyer)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('buyer',array($buyer->id)) }}">
                    <td>
                      <b>{{ $buyer->first_name }} {{ $buyer->last_name }}</b>
                    </td>
                    <td>
                        @if($buyer->home_address)
                          {{ $buyer->home_address }} </br>
                        @endif

                        @if($buyer->contact_number_mobile)
                          {{ $buyer->contact_number_mobile }} </br>
                        @endif

                        @if($buyer->email)
                          {{ $buyer->email }}
                        @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No buyers found
            @endif
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="list-group">
      @if(count($buyers) > 0)
        @foreach($buyers as $buyer)
          <a href="{{ URL::route('buyer',array($buyer->id)) }}" class="list-group-item">
            {{ $buyer->last_name}}, {{ $buyer->first_name }} {{ $buyer->middle_name }}
          </a>
        @endforeach
      @else
        No buyers found
      @endif
    </div> -->
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