<script>
   $(document).ready(function(){
    $("#deletePropertyButton").click(function(){
        $('#deletePropertyModal').modal('show');
    });
    $("#closeDeletePropertyButton").click(function(){
        $('#deletePropertyModal').modal('hide');
    });
  });
</script>

<div id="deletePropertyModal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Danger!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this property?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDeletePropertyButton">Close</button>
        <a href="{{ URL::route('delete_property',$property->slug) }}" class="btn btn-default">Delete Property</a>
      </div>
    </div>
  </div>
</div>