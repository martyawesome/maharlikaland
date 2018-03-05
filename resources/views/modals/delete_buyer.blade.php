<script>
   $(document).ready(function(){
    $("#deleteButton").click(function(){
        $('#deleteModal').modal('show');
    });
    $("#sureDeleteButton").click(function(){
        $('#deleteFinalModal').modal('show');
    });
    
    $("#closeDeleteButton").click(function(){
        $('#deleteModal').modal('hide');
    });
    $("#closeDeleteFinalButton").click(function(){
        $('#deleteModal').modal('hide');
        $('#deleteFinalModal').modal('hide');
    });
    $("#final-submit-button").click(function(){
        $.ajax({
            type: "POST",
            url: "{{ url('/') }}/manage/developers/prospect_buyer/{{$prospect_buyer->id}}/delete",
            data: {
              security_code : $('#security_code').val()
            },
            success: function (data) {
              if(data == 1){ 
                window.location="{{route('prospect_buyers')}}";
              } else {
                if(data == 0){
                  $('#invalid-modal-message').html('Invalid security code');
                } else {
                  $('#invalid-modal-message').html('Something went wrong while deleting. Please, try again.');
                }
                $('#invalid-modal').modal('show');
              } 
            },
            error: function (data) {
              $('#invalid-modal-message').html('Something went wrong with the server. Please, try again later.');
              $('#invalid-modal').modal('show');
            }
        });
    });
  });
</script>     

<div id="deleteModal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Danger!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDeleteButton">Close</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="sureDeleteButton">Delete</button>
      </div>
    </div>
  </div>
</div>

<div id="deleteFinalModal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Security</h4>
      </div>
        <div class="modal-body">
          <div class="form-group">
            {!! Form::label('security_code', 'Security Code*'); !!}
            {!! Form::password('security_code', ['class'=>'form-control', 'id' => 'security_code']) !!}
          </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDeleteFinalButton">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="final-submit-button">Submit</button>
        </div>
    </div>
  </div>
</div>

<div id="invalidModal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p id='invalid-modal-message'>Invalid security code</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>