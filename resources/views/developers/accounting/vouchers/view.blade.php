@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.delete_voucher')
  <section class="content-header">
    <h1>
      Voucher
      <small><b><a href="{{ URL::route('vouchers', array($project->slug)) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li>Vouchers</li>
      <li>{{ $voucher->voucher_number }}</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.accounting.vouchers.view')
      <div class="box-footer" style="margin-bottom:15px;">
        {!! link_to_route('edit_voucher', 'Edit', array($voucher->voucher_number), ['class' => 'btn btn-primary']) !!}
        @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
        or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
          <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
        @endif
      </div>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Particulars</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              @if(count($voucher_details) > 0)
              <table id="vouchers" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Account Title</th>
                    <th>Amount</th>
                    <th>Property</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($voucher_details as $voucher_detail)
                    <tr class='clickable-row' style="cursor: pointer;" data-href="{{ URL::route('edit_voucher_detail',array($project->slug, $voucher->voucher_number, $voucher_detail->id)) }}">
                      <td>{{ $voucher_detail->account_title }}</td>
                      <td>{{ number_format($voucher_detail->amount, 2, '.', ',') }}</td>
                      @if($voucher_detail->property_id != 0 and $voucher_detail->property_id != null)
                        <td>{{ $voucher_detail->property }}</td>
                      @else
                        <td></td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                No vouchers found
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer" style="margin-bottom:15px;">
        {!! link_to_route('add_voucher_detail', 'Add', array($voucher->voucher_number), ['class' => 'btn btn-primary']) !!}
      </div>
  </section>
  <script>
      $(function () {
        $('#vouchers').DataTable({
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