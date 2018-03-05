@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Penalty Calculator
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">Penalty Calculator</li>
    </ol>
  </section>
  <section class="content">
    
      <div class="box box-primary">
        <div class="box-body">
          <div class="form-group">
            {!! Form::label('ma', 'Monthy Amortization'); !!}
            {!! Form::text('ma', null, ['class' => 'form-control']) !!}
          </div>
        </div> 
      </div>
      <div class="box-footer">
        <button name="calculate-form-button" id="calculate-form-button" class="btn btn-primary">Calculate</button>
      </div>
      <div class="box box-primary">
        <div class="box-body">
          <div id="penalties_container">
          </div>
        </div>
      </div>
  </section>
  <script type="text/javascript">
    var penalty_percentage = "<?php echo config('constants.PENALTY_PERCENTAGE'); ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/penalty_calculator.js') }}"></script>
@stop