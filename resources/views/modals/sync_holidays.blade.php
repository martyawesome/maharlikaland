<div id="confirmation-modal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Danger!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to sync the holidays? Previous holidays will be deleted.</p>
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
<div id="success-modal" class="modal modal-success fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Success!</h4>
      </div>
      <div class="modal-body">
        <p id='success-modal-message'>Sync success!</p>
      </div>
      <div class="modal-footer">
        <button id="success-modal-button" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>