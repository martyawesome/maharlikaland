<script>
   $(document).ready(function(){
    $("#import_ledger_button").click(function(){
      $('#myLedgerModal').modal();
    });
  });
</script>
<div id="myLedgerModal" class="modal modal-default fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Ledger Account for {{  $property->name }}</h4>
      </div>
      {!! Form::model(null, array('files' => true)) !!}
        <div class="modal-body">
          <div class="form-group">
            {!! Form::label('Excel File'); !!}
            {!! Form::file('ledger_excel'); !!}
          </div>
        </div>
        <div class="modal-footer">
          {!! Form::submit('Upload', ['class' => 'btn btn-success'])!!}  
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>