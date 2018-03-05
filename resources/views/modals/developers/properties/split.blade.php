<script>
   $(document).ready(function(){
    $("#split-button").click(function(){
      $('#confirmation-modal').modal('show');
    });
    $("#sure-confirmation-button").click(function(){
      $('#input-modal').modal('show');
    });
    $("#submit-input-button").click(function(){
      var expected_outcome = $('#expected_outcome').val();
      if(Math.floor(expected_outcome) == expected_outcome && $.isNumeric(expected_outcome) && expected_outcome > 1)
      { 
        $('#security-code-modal').modal('show');
      }  else {
        $('#invalid-input-modal').modal('show');
      }
    });
    $("#submit-security-code-button").click(function(){
      $.ajax({
        type: "POST",
        url: "{{ url('/') }}/manage/developers/projects/{{ $project->slug }}/{{ $property->slug }}/edit/split",
        data: {
          security_code : $('#security_code').val()
        },
        success: function (data) {
          if(data == 1){ 
            window.location="{{route('project_block',array($project->slug, $property_location->block_number))}}";
          } else if(data == 2){
            alert("Something went wrong while splitting the lot. Please, try again.");
          } else {
            $('#invalid-security-code-modal').modal('show');
          }
        },
        error: function (data) {
          alert("Something went wrong with the server. Please, try again.");
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
        <p>Are you sure you want to split this lot? There is no turning back.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close-confirmation-button">Close</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" id="sure-confirmation-button">Sure</button>
      </div>
    </div>
  </div>
</div>

<div id="input-modal" class="modal modal-success fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Lots</h4>
      </div>
        <div class="modal-body">
          <div class="form-group">
            {!! Form::label('expected_outcome', 'Expected Outcome*'); !!}
            {!! Form::text('expected_outcome',2,['class'=>'form-control', 'id' => 'expected_outcome']) !!}
          </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-input-button">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-input-button">Submit</button>
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
<div id="invalid-input-modal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Invalid input</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="invalid-security-code-modal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Invalid security code</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>