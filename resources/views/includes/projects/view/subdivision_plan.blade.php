<div>
  <h3 class="form-header">
    Subdivision Plan
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
    	<small><a href="{{ URL::route('project_edit_subd_plan',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success">
    <div class="box-body">
      @if($subd_plan)
      <div class="box-body">
          <div class="selected-image-main-photo">
            <img class="img-responsive" src="<?php echo asset("").$subd_plan->image_path?>" alt="Gallery picture">
          </div> 
      </div>
      @endif
    </div> 
  </div>
</div> 