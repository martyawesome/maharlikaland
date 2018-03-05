<div>
  <h3 class="form-header">
    Incentives
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_incentives',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      @foreach($incentives as $incentive)
        <div class="control-group">
          <div class="controls readonly">{{ $incentive->incentive }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div> 