<div style="margin-top:30px;">
  <h3 class="form-header">
    Basic Info
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_basic_info',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls readonly">{{ $project->name }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Project Type</label>
        <div class="controls readonly">{{ $project->project_type }}</div>
      </div>
      @if($project->overview)
        <div class="control-group">
          <label class="control-label">Overview</label>
          <div class="controls readonly">{{ $project->overview }}</div>
        </div>
      @endif
      @if($project->development_date != "0000-00-00")
        <div class="control-group">
          <label class="control-label">Development Date</label>
          <div class="controls readonly">{{ $project->development_date }}</div>
        </div>
      @endif
      @if($project->opening_date != "0000-00-00")
        <div class="control-group">
          <label class="control-label">Opening Date</label>
          <div class="controls readonly">{{ $project->opening_date }}</div>
        </div>
      @endif
      <div class="control-group">
        <label class="control-label">Pre-selling</label>
        <div class="controls readonly">
          @if($project->is_preselling)
            Yes
          @else
            No
          @endif
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Active</label>
        <div class="controls readonly">
          @if($project->is_active)
            Yes
          @else
            No
          @endif
        </div>
      </div>
    </div>
  </div> 
</div>