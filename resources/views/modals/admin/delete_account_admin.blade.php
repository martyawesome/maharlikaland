<script>
   $(document).ready(function(){
    $("#deleteAccountButton").click(function(){
        $('#deleteAccountModal').modal('show');
    });
    $("#closeDeleteAccountButton").click(function(){
        $('#deleteAccountModal').modal('hide');
    });
  });
</script>

<div id="deleteAccountModal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Danger!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this account?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDeleteAccountButton">Close</button>
        <a href="{{ URL::route('admin_delete_account_admin',$user->username) }}" class="btn btn-default">Delete Account</a>
      </div>
    </div>

  </div>
</div>