<div>
  <h3 class="form-header">
    Amenities
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <small><a href="{{ URL::route('project_edit_amenities',$project->slug) }}">Edit</a></small>
    @endif
  </h3>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      @foreach($amenities as $amenity)
        <div class="control-group">
          <div class="controls readonly">{{ $amenity->amenity }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div> 