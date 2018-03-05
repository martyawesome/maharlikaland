<div class="box box-success">
  <div class="box-body" style="padding-top:0px;">
    <div class="control-group">
      <label class="control-label">PRC License Number</label>
      <div class="controls readonly">{{ $agent->prc_license_number }}</div>
    </div>
    @if($agent->facebook_url)
	    <div class="control-group">
	      <label class="control-label">Facebook URL</label>
	      <div class="controls readonly">{{ $agent->facebook_url }}</div>
	    </div>
    @endif
    @if($agent->twitter_url)
	    <div class="control-group">
	      <label class="control-label">Twitter URL</label>
	      <div class="controls readonly">{{ $agent->twitter_url }}</div>
	    </div>
    @endif
    @if($agent->linkdin_url)
	    <div class="control-group">
	      <label class="control-label">LinkedIn URL</label>
	      <div class="controls readonly">{{ $agent->linkdin_url }}</div>
	    </div>
    @endif
  </div>
</div> 