<div class="box box-primary">
  <div class="box-body">
    <div class="form-group">
      {!! Form::label('date', 'Date*'); !!}
        @if(old('date'))
          {!! Form::text('date', $voucher->date, ['class' => 'form-control pull-right', 'style' => 'margin-bottom:10px;']) !!}
        @else
          @if($voucher->date != null and $voucher->date != "0000-00-00")
            {!! Form::text('date', $voucher->date, ['class' => 'form-control pull-right', 'style' => 'margin-bottom:10px;']) !!}
          @else
            {!! Form::text('date', date("Y-m-d"), ['class' => 'form-control pull-right', 'style' => 'margin-bottom:10px;']) !!}
          @endif
        @endif
       {!! $errors->first('date', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('voucher_number') ? ' has-error' : '' }}">
      {!! Form::label('voucher_number', 'Voucher Number*'); !!}
      {!! Form::text('voucher_number', $voucher->voucher_number, ['class' => 'form-control']) !!}
      {!! $errors->first('voucher_number', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('payee') ? ' has-error' : '' }}">
      {!! Form::label('payee', 'Payee*'); !!}
      {!! Form::text('payee', $voucher->payee, ['class' => 'form-control']) !!}
      {!! $errors->first('payee', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('issued_by') ? ' has-error' : '' }}">
      {!! Form::label('issued_by', 'Issued By*'); !!}
      {!! Form::text('issued_by', $voucher->issued_by, ['class' => 'form-control']) !!}
      {!! $errors->first('issued_by', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('received_by') ? ' has-error' : '' }}">
      {!! Form::label('received_by', 'Received By*'); !!}
      {!! Form::text('received_by', $voucher->received_by, ['class' => 'form-control']) !!}
      {!! $errors->first('received_by', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>
<script type="text/javascript">
  $(function(){
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>