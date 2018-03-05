@if(Session::has('hasPropertySuccessfullyCreated') and Session::has('message'))
  <script>
     $(document).ready(function(){
      $('#myModal').modal();
    });
  </script>
  <div id="myModal" class="modal modal-success fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success!</h4>
        </div>
        <div class="modal-body">
          <p>{!! Session::get('message') !!}</p>
        </div>
        <div class="modal-footer">
          {!! link_to_route('agent_all_properties', 'No', null, array('class' => 'btn btn-success')) !!}
          {!! link_to_route('property_upload_images', 'Yes', [urlencode($property->slug)], array('class' => 'btn btn-success')) !!}
        </div>
      </div>
    </div>
  </div>
@endif