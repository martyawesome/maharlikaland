<script>
   $(document).ready(function(){
    $("#delete-button").click(function(){
        $('#confirmation-modal').modal('show');
    });
    $("#sure-confirmation-button").click(function(){
        $('#security-code-modal').modal('show');
    });
    $("#submit-security-code-button").click(function(){
         $.ajax({
            type: "POST",
            url: "{{ url('/') }}/manage/developers/projects/{{ $project->slug }}/delete/nearby_establishments/{{ $nearby_establishment->slug }}",
            data: {
              security_code : $('#security_code').val()
            },
            success: function (data) {
              if(data == 1){ 
                window.location="{{route('project_edit_nearby_establishments',array($project->slug))}}";
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

<div id="confirmation-modal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Danger!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this? There is no turning back.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-confirmation-button">Close</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="sure-confirmation-button">Delete</button>
      </div>
    </div>
  </div>
</div>
<div id="security-code-modal" class="modal modal-warning fade" role="dialog">
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
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-security-code-button">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-security-code-button">Submit</button>
        </div>
    </div>
  </div>
</div>
<div id="invalid-modal" class="modal modal-danger fade" role="dialog">
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