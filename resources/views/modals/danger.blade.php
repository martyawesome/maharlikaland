@if(Session::has('danger'))
  <script>
     $(document).ready(function(){
      $("#closeDangerModal").click(function(){
          $('#dangerModal').modal('hide');
      });
      $('#dangerModal').modal();
    });
  </script>

  <div id="dangerModal" class="modal modal-danger fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Error!</h4>
        </div>
        <div class="modal-body">
          <p>{!! Session::get('danger') !!} </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDangerModal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endif