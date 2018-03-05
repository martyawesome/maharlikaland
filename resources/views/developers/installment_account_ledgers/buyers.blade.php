@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Buyers
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">Buyers</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    <div class="list-group">
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
                  @if($type == 1)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('new_ledger',array($buyer->id)) }}">
                  @elseif($type == 2)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('ledger_properties',array($buyer->id)) }}">
                  @endif
                      <td>
                          <b>{{ $buyer->first_name }} {{ $buyer->last_name }}</b>
                        </td>
                        <td>
                            {{ $buyer->home_address }} </br>
                            {{ $buyer->contact_number_mobile }} </br>
                            {{ $buyer->email }}
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
     <!--  @if(count($buyers) > 0)
        @foreach($buyers as $buyer)
          @if($type == 1)
            <a href="{{ URL::route('new_ledger',array($buyer->id)) }}" class="list-group-item">
          @elseif($type == 2)
            <a href="{{ URL::route('ledger_properties',array($buyer->id)) }}" class="list-group-item">
          @endif
            {{ $buyer->last_name}}, {{ $buyer->first_name }} {{ $buyer->middle_name }}
          </a>
        @endforeach
      @else
        No buyers found
      @endif -->
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