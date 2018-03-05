<div>
  <h3 class="form-header">
    Nearby Establishments
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_nearby_establishments',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      @foreach($nearby_establishments as $nearby_establishment)
        <div class="control-group">
          <div class="controls readonly">{{ $nearby_establishment->nearby_establishment }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div> 