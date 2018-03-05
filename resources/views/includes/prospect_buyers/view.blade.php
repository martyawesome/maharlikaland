<div>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls readonly">{{ $prospect_buyer->last_name }}, {{ $prospect_buyer->first_name }} {{ $prospect_buyer->middle_name }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Prospect Property</label>
        <div class="controls readonly">{{ $prospect_property->name }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Sex</label>
        <div class="controls readonly">{{ $prospect_buyer->sex }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Home Address</label>
        <div class="controls readonly">{{ $prospect_buyer->address }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Contact Number - Mobile</label>
        <div class="controls readonly">{{ $prospect_buyer->contact_number }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls readonly">{{ $prospect_buyer->email }}</div>
      </div>
    </div>
  </div> 
</div>