@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Prospect Buyers
    </h1>
    <ol class="breadcrumb">
      <li>Prospect Buyers</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            @if(count($prospect_buyers) > 0)
            <table id="objects" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                @foreach($prospect_buyers as $prospect_buyer)
                  <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('prospect_buyer',array($prospect_buyer->id)) }}">
                    <td>
                      <b>{{ $prospect_buyer->first_name }} {{ $prospect_buyer->last_name }}</b>
                    </td>
                    <td>
                        {{ $prospect_buyer->address }} </br>
                        {{ $prospect_buyer->contact_number }} </br>
                        {{ $prospect_buyer->email }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @else
              No prospect buyers found
            @endif
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="list-group">
      @if(count($prospect_buyers) > 0)
        @foreach($prospect_buyers as $prospect_buyer)
          <a href="{{ URL::route('prospect_buyer',array($prospect_buyer->id)) }}" class="list-group-item">
            {{ $prospect_buyer->last_name}}, {{ $prospect_buyer->first_name }} {{ $prospect_buyer->middle_name }}
          </a>
        @endforeach
      @else
        No prospect buyers found
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