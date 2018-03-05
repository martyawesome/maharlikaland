<div>
  <h3 class="form-header">
    Location
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_location',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Province</label>
        <div class="controls readonly">{{ $project->province }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">City/Municipality</label>
        <div class="controls readonly">{{ $project->city_municipality }}</div>
      </div>
      @if($project->barangay != null or $project->barangay != "")
        <div class="control-group">
          <label class="control-label">Barangay</label>
          <div class="controls readonly">{{ $project->barangay }}</div>
        </div>
      @endif
      @if($project->street != null or $project->street != "")
        <div class="control-group">
          <label class="control-label">Street</label>
          <div class="controls readonly">{{ $project->street }}</div>
        </div>
      @endif
      @if($project->coordinates != null or $project->coordinates != "")
        <div class="control-group">
          <label class="control-label">Coordinates</label>
          <div class="controls readonly">{{ $project->coordinates }}</div>
        </div>
      @endif
      @if($project->remarks != null or $project->remarks != "")
        <div class="control-group">
          <label class="control-label">Remarks</label>
          <div class="controls readonly">{{ $project->remarks }}</div>
        </div>
      @endif
    </div>
  </div> 
</div> 