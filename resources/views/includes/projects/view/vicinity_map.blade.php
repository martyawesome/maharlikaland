<div>
  <h3 class="form-header">
    Vicinity Map
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
    	<small><a href="{{ URL::route('project_edit_vicinity_map',$project->slug) }}">Edit</a></small>
    @endif
  </h3> 
  <div class="box box-success">
    <div class="box-body">
      @if($vicinity_map_project)
      <div class="box-body">
          <div class="selected-image-main-photo">
            <img class="img-responsive" src="<?php echo asset("").$vicinity_map_project->image_path?>" alt="Gallery picture">
          </div> 
      </div>
      @endif
    </div> 
  </div>
</div> 