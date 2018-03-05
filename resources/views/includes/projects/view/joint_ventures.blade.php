<div>
  <h3 class="form-header">
    Joint Ventures
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_joint_ventures',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      @foreach($joint_ventures as $joint_venture)
        <div class="control-group">
          <div class="controls readonly">{{ $joint_venture->name }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div> 