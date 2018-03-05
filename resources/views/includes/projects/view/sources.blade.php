<div>
  <h3 class="form-header">
    Sources
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
     <small><a href="{{ URL::route('project_edit_sources',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Electricity</label>
        <div class="controls readonly">{{ $project->electricity_source }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Water</label>
        <div class="controls readonly">{{ $project->water_source }}</div>
      </div>
    </div>
  </div> 
</div>