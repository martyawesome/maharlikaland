<script>
   $(document).ready(function(){
    $("#import_button").click(function(){
      $('#myModal').modal();
    });
  });
</script>
<div id="myModal" class="modal modal-default fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Buyers</h4>
      </div>
      {!! Form::model(null, array('files' => true)) !!}
        <div class="modal-body">
          <div class="form-group">
            {!! Form::label('Excel File'); !!}
            {!! Form::file('excel'); !!}
          </div>
        </div>
        <div class="modal-footer">
          {!! Form::submit('Upload', ['class' => 'btn btn-success'])!!}  
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>