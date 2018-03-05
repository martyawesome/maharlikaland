<div id="left">
  <div class="user-profile-photo-container">
    <img class="user-profile-photo img-responsive" src="<?php echo asset("").$user->profile_picture_path?>" />
  </div>
</div>
<div id="right">
  <div class="box box-success">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls readonly">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</div>
      </div>
      @if($user->nickname)
        <div class="control-group">
          <label class="control-label">Nickname</label>
          <div class="controls readonly">{{ $user->nickname }}</div>
        </div>
      @endif
      <div class="control-group">
        <label class="control-label">User Type</label>
        <div class="controls readonly">{{ $user_type->user_type }}</div>
      </div>
      @if($user->username)
        <div class="control-group">
          <label class="control-label">Username</label>
          <div class="controls readonly">{{ $user->username }}</div>
        </div>
      @endif
      @if($user->email)
        <div class="control-group">
          <label class="control-label">Email</label>
          <div class="controls readonly">{{ $user->email }}</div>
        </div>
      @endif
      <div class="control-group">
        <label class="control-label">Sex</label>
        <div class="controls readonly">{{ $user->sex }}</div>
      </div>
      @if($user->birthdate)
        <div class="control-group">
          <label class="control-label">Birthdate</label>
          <div class="controls readonly">{{ date('F j, Y', strtotime($user->birthdate))}}</div>
        </div>
      @endif
      @if($user->address)
        <div class="control-group">
          <label class="control-label">Address</label>
          <div class="controls readonly">{{ $user->address }}</div>
        </div>
      @endif
      @if($user->contact_number)
        <div class="control-group">
          <label class="control-label">Contact Number</label>
          <div class="controls readonly">{{ $user->contact_number }}</div>
        </div>
      @endif
    </div>
  </div> 
</div>