@if(Session::has('success'))
  <script>
     $(document).ready(function(){
      $("#closeSuccessModal").click(function(){
          $('#successfulModal').modal('hide');
      });
      $('#successfulModal').modal();
    });
  </script>

  <div id="successfulModal" class="modal modal-success fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success!</h4>
        </div>
        <div class="modal-body">
          <p>{!! Session::get('success') !!} </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeSuccessModal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endif