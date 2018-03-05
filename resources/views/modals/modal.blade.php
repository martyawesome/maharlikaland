@if(Session::has('modal'))
  <script>
     $(document).ready(function(){
      $("#closeModal").click(function(){
          $('#myModal').modal('hide');
      });
      $('#myModal').modal();
    });
  </script>

  <div id="myModal" class="modal modal-default fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Message</h4>
        </div>
        <div class="modal-body">
          <p>{!! Session::get('modal') !!} </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endif