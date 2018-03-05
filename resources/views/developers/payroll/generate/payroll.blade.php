@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Payroll for {{ $formatted_begin }} - {{ $formatted_end }}
    </h1>
    <ol class="breadcrumb">
      <li>Payroll</li>
      <li class="active">{{ $formatted_begin }} - {{ $formatted_end }}</li>
    </ol>
  </section>
  <section class="content">
    <div class="col-xs-12">
      <h4>Office Payroll</h4>
      <div class="box">
        <div class="box-body" style="overflow: auto;">
          @if(count($payroll_records_office) > 0)
            <table id="objects" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Salary Rate</th>
                  @for($i = 0; $i < count($payroll_records_office); $i++)
                    <th>{{ $payroll_records_office[$i][0]["date"] }}</th>
                  @endfor
                  <th>Cash Advance</th>
                  <th>Deductions</th>
                  <th>Additions</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                  <?php $grand_total_office = 0;?>
                  @for($i = 1; $i < count($payroll_records_office[0]); $i++)
                    <tr>
                      <td>{{ $payroll_records_office[0][$i]["employee_name"] }}</td>
                      <td>{{ $payroll_records_office[0][$i]["rate"] }}</td>
                      <?php
                        $total = 0;
                        $total_additions = 0;
                        $total_deductions = 0;
                      ?>
                      @for($k = 0; $k < count($payroll_records_office); $k++)
                        <td>
                          @if($payroll_records_office[$k][$i]["total"] > 0)
                            <?php
                              $total += $payroll_records_office[$k][$i]["total"];
                              $total_deductions += $payroll_records_office[$k][$i]["deduction"];
                              $total_additions += $payroll_records_office[$k][$i]["addition"];
                            ?> 
                            {{ $payroll_records_office[$k][$i]["hours_of_work"] }} hr
                            <!-- Php {{ floor($payroll_records_office[$k][$i]["total"] * 100) / 100 }}
                             ({{ $payroll_records_office[$k][$i]["hours_of_work"] }} hours) -->
                          @else
                            0
                          @endif
                        </td>
                      @endfor
                      <td>
                        @if($remaining_ca_office[$i-1] != 0)
                          <?php echo config('constants.CURRENCY'); ?> {{ number_format($remaining_ca_office[$i-1], 2, '.', ',') }}
                        @else
                          0
                        @endif
                      </td>
                      <td>
                        <?php 
                        if($total_deductions > 0)
                          echo config('constants.CURRENCY');
                        ?> {{  number_format($total_deductions, 2, '.', ',') }}
                      </td>
                      <td>
                        <?php 
                        if($total_additions > 0)
                          echo config('constants.CURRENCY');
                        ?>
                        {{ number_format($total_additions, 2, '.', ',') }}
                      </td>
                      <td>
                        <?php
                          if($remaining_ca_office[$i-1] >= $total)
                            $total = 0;
                          else
                            $total -= $remaining_ca_office[$i-1];

                          $total = floor(($total + $total_additions - $total_deductions) * 100) / 100;

                          if($total > 0)
                            echo config('constants.CURRENCY');
                          echo ' '.number_format($total, 2, '.', ',');
                          $grand_total_office += $total;
                        ?>
                      </td>
                    </tr>
                  @endfor
                  <tr>
                    <td></td>
                    <td></td>
                    @for($k = 1; $k < count($payroll_records_office); $k++)
                      <td>
                      </td>
                    @endfor
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>TOTAL</b></td>
                    <td>
                      <b><?php
                      if(floor($grand_total_office * 100) / 100 > 0)
                          echo config('constants.CURRENCY');
                      echo ' '.number_format(floor(($grand_total_office * 100) / 100), 2, '.', ',')?></b>
                    </td>
                  </tr>
              </tbody>
            </table>
          @else
            No payroll found
          @endif
        </div>
      </div>
      <h4>Construction Payroll</h4>
      <div class="box">
        <div class="box-body" style="overflow: auto;">
          @if(count($payroll_records_construction) > 0)
            <table id="objects" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Salary Rate</th>
                  @for($i = 0; $i < count($payroll_records_construction); $i++)
                    <th>{{ $payroll_records_construction[$i][0]["date"] }}</th>
                  @endfor
                  <th>Cash Advance</th>
                  <th>Deductions</th>
                  <th>Additions</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                  <?php $grand_total_construction = 0;?>
                  @for($i = 1; $i < count($payroll_records_construction[0]); $i++)
                    <tr>
                      <td>{{ $payroll_records_construction[0][$i]["employee_name"] }}</td>
                      <td>{{ $payroll_records_construction[0][$i]["rate"] }}</td>
                      <?php
                        $total = 0;
                        $total_cash_advance = 0;
                      ?>
                      @for($k = 0; $k < count($payroll_records_construction); $k++)
                        <td>
                          @if($payroll_records_construction[$k][$i]["total"] > 0)
                            <?php
                              $total += $payroll_records_construction[$k][$i]["total"];
                              $total_deductions += $payroll_records_construction[$k][$i]["deduction"];
                              $total_additions += $payroll_records_construction[$k][$i]["addition"];
                            ?> 
                            <!-- Php {{ floor($payroll_records_construction[$k][$i]["total"] * 100) / 100 }}
                             ({{ $payroll_records_construction[$k][$i]["hours_of_work"] }} hours) -->
                             {{ $payroll_records_construction[$k][$i]["hours_of_work"] }} hr
                          @else
                            0
                          @endif
                        </td>
                      @endfor
                      <td>
                        @if($remaining_ca_construction[$i-1] != 0)
                          <?php echo config('constants.CURRENCY'); ?> {{ number_format($remaining_ca_construction[$i-1], 2, '.', ',') }}
                        @else
                          0
                        @endif
                      </td>
                      <td>
                      <?php 
                        if($total_deductions > 0)
                          echo config('constants.CURRENCY');
                        ?> {{ number_format($total_deductions, 2, '.', ',') }}
                    </td>
                    <td>
                      <?php 
                        if($total_additions > 0)
                          echo config('constants.CURRENCY');
                        ?> {{ number_format($total_additions, 2, '.', ',') }}
                    </td>
                      <td>
                        <?php
                          if($remaining_ca_construction[$i-1] >= $total)
                            $total = 0;
                          else
                            $total -= $remaining_ca_construction[$i-1];

                          $total = floor(($total + $total_additions - $total_deductions) * 100) / 100;

                          if($total > 0)
                            echo config('constants.CURRENCY');
                          echo ' '.number_format($total, 2, '.', ',');

                          $grand_total_construction += $total;
                        ?>
                      </td>
                    </tr>
                  @endfor
                  <tr>
                    <td></td>
                    <td></td>
                    @for($k = 1; $k < count($payroll_records_construction); $k++)
                      <td>
                      </td>
                    @endfor
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>TOTAL</b></td>
                    <td>
                      <b><?php
                          if(floor($total * 100) / 100 > 0)
                            echo config('constants.CURRENCY');
                          echo ' '.number_format(floor($grand_total_construction * 100) / 100, 2, '.', ',');
                          ?>
                      </b>
                    </td>
                  </tr>
              </tbody>
            </table>
          @else
            No payroll found
          @endif
        </div>
      </div>
      <h4>Guard Payroll</h4>
      <div class="box">
        <div class="box-body" style="overflow: auto;">
          @if(count($payroll_records_guards) > 0)
            <table id="objects" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Salary Rate</th>
                  @for($i = 0; $i < count($payroll_records_guards); $i++)
                    <th>{{ $payroll_records_guards[$i][0]["date"] }}</th>
                  @endfor
                  <th>Cash Advance</th>
                  <th>Deductions</th>
                  <th>Additions</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                  <?php $grand_total_guards = 0;?>
                  @for($i = 1; $i < count($payroll_records_guards[0]); $i++)
                    <tr>
                      <td>{{ $payroll_records_guards[0][$i]["employee_name"] }}</td>
                      <td>{{ $payroll_records_guards[0][$i]["rate"] }}</td>
                      <?php
                        $total = 0;
                        $total_cash_advance = 0;
                      ?>
                      @for($k = 0; $k < count($payroll_records_guards); $k++)
                        <td>
                          @if($payroll_records_guards[$k][$i]["total"] > 0)
                            <?php
                              $total += $payroll_records_guards[$k][$i]["total"];
                              $total_deductions += $payroll_records_guards[$k][$i]["deduction"];
                              $total_additions += $payroll_records_guards[$k][$i]["addition"];
                            ?> 
                            <!-- Php {{ floor($payroll_records_guards[$k][$i]["total"] * 100) / 100 }}
                             ({{ $payroll_records_guards[$k][$i]["hours_of_work"] }} hours) -->
                             {{ $payroll_records_guards[$k][$i]["hours_of_work"] }} hr
                          @else
                            0
                          @endif
                        </td>
                      @endfor
                      <td>
                        @if($remaining_ca_guards[$i-1] != 0)
                          <?php echo config('constants.CURRENCY'); ?> {{ number_format($remaining_ca_guards[$i-1], 2, '.', ',') }}
                        @else
                          0
                        @endif
                      </td>
                      <td>
                      <?php 
                        if($total_deductions > 0)
                          echo config('constants.CURRENCY');
                        ?> {{ number_format($total_deductions, 2, '.', ',') }}
                    </td>
                    <td>
                      <?php 
                        if($total_additions > 0)
                          echo config('constants.CURRENCY');
                        ?> {{ number_format($total_additions, 2, '.', ',') }}
                    </td>
                      <td>
                        <?php
                          if($remaining_ca_guards[$i-1] >= $total)
                            $total = 0;
                          else
                            $total -= $remaining_ca_guards[$i-1];

                          $total = floor(($total + $total_additions - $total_deductions) * 100) / 100;

                          if($total > 0)
                            echo config('constants.CURRENCY');
                          echo ' '.number_format($total, 2, '.', ',');

                          $grand_total_guards += $total;
                        ?>
                      </td>
                    </tr>
                  @endfor
                  <tr>
                    <td></td>
                    <td></td>
                    @for($k = 1; $k < count($payroll_records_guards); $k++)
                      <td>
                      </td>
                    @endfor
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>TOTAL</b></td>
                    <td>
                      <b><?php
                          if(floor($total * 100) / 100 > 0)
                            echo config('constants.CURRENCY');
                          
                          echo ' '.number_format(floor($grand_total_guards * 100) / 100, 2, '.', ',');
                          ?>
                      </b>
                    </td>
                  </tr>
              </tbody>
            </table>
          @else
            No payroll found
          @endif
        </div>
      </div>
      <div class="box">
        <div class="box-body" style="padding-right: 20px; text-align: right;">
          <h4>
            Total: 
            <b>
              <?php 
              if ($grand_total_office + $grand_total_construction + $grand_total_guards > 0)
                echo config('constants.CURRENCY');
              ?>
              {{ number_format($grand_total_office + $grand_total_construction + $grand_total_guards, 2, '.', ',') }}
              </b>
          </h4>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(function () {
      $('#objects').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": true
      });
    });
  </script>
@stop


