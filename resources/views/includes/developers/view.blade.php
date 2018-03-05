<div id="left">
  <div class="user-profile-photo-container">
    <img class="user-profile-photo img-responsive" src="<?php echo asset("").$developer->logo_path?>" >
  </div>
</div>
<div id="right">
  <div class="box box-success">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Developer</label>
        <div class="controls readonly">{{ $developer->name }}</div>
      </div>
      @if($developer->overview)
        <div class="control-group">
          <label class="control-label">Overview</label>
          <div class="controls readonly">{{ $developer->overview }}</div>
        </div>
      @endif
      @if($developer->mission)
        <div class="control-group">
          <label class="control-label">Mission</label>
          <div class="controls readonly">{{ $developer->mission }}</div>
        </div>
      @endif
      @if($developer->vission)
        <div class="control-group">
          <label class="control-label">Vision</label>
          <div class="controls readonly">{{ $developer->vission }}</div>
        </div>
      @endif
      @if($developer->address)
        <div class="control-group">
          <label class="control-label">Address</label>
          <div class="controls readonly">{{ $developer->address }}</div>
        </div>
      @endif
      @if($developer->contact_number)
        <div class="control-group">
          <label class="control-label">Contact Number</label>
          <div class="controls readonly">{{ $developer->contact_number }}</div>
        </div>
      @endif
      @if($developer->email)
        <div class="control-group">
          <label class="control-label">Email</label>
          <div class="controls readonly">{{ $developer->email }}</div>
        </div>
      @endif
      @if($developer->facebook_url)
        <div class="control-group">
          <label class="control-label">Facebook</label>
          <div class="controls readonly">{{ $developer->facebook_url }}</div>
        </div>
      @endif
      @if($developer->twitter_url)
        <div class="control-group">
          <label class="control-label">Twitter</label>
          <div class="controls readonly">{{ $developer->twitter_url }}</div>
        </div>
      @endif
      @if($developer->linkdin_url)
        <div class="control-group">
          <label class="control-label">LinkdIn</label>
          <div class="controls readonly">{{ $developer->linkdin_url }}</div>
        </div>
      @endif
    </div>
  </div> 
</div>