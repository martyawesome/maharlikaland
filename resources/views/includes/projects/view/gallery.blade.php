<div>
  <h3 class="form-header">
    Gallery
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
    	<small><a href="{{ URL::route('project_gallery',$project->slug) }}">Edit</a></small>
    @endif
  </h3> 
  @include('includes.projects.view.gallery_items')
</div> 

