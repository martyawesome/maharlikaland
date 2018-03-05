<div>
  <div class="box box-success" style="margin-top:10px;">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls readonly">{{ $buyer->last_name }}, {{ $buyer->first_name }} {{ $buyer->middle_name }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Properties</label>
        @if(count($properties) == 0)
          <div class="controls readonly">None</div>
        @else
          @foreach($properties as $property)
            <div class="controls readonly">{{ $property->name }}</div>
          @endforeach
        @endif
      </div>
      <div class="control-group">
        <label class="control-label">Sex</label>
        <div class="controls readonly">{{ $buyer->sex }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Home Address</label>
        <div class="controls readonly">{{ $buyer->home_address }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Contact Number - Mobile</label>
        <div class="controls readonly">{{ $buyer->contact_number_mobile }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Contact Number - Home</label>
        <div class="controls readonly">{{ $buyer->contact_number_home }}</div>
      </div>
      <div class="control-group">
        <label class="control-label">Contact Number - Office</label>
        <div class="controls readonly">{{ $buyer->contact_number_office }}</div>
      </div>
      @if($buyer->email)
        <div class="control-group">
          <label class="control-label">Email</label>
          <div class="controls readonly">{{ $buyer->email }}</div>
        </div>
      @endif
      @if($buyer->civil_status)
        <div class="control-group">
          <label class="control-label">Civil Status</label>
          <div class="controls readonly">{{ $buyer->civil_status }}</div>
        </div>
      @endif
      @if($buyer->birthdate and $buyer->birthdate != "0000-00-00")
        <div class="control-group">
          <label class="control-label">Birthdate</label>
          <div class="controls readonly">{{ $buyer->birthdate }}</div>
        </div>
      @endif
      @if($buyer->spouse_name)
        <div class="control-group">
          <label class="control-label">Spouse's name</label>
          <div class="controls readonly">{{ $buyer->spouse_name }}</div>
        </div>
      @endif
      @if($buyer->num_of_children)
        <div class="control-group">
          <label class="control-label">Number of Children</label>
          <div class="controls readonly">{{ $buyer->num_of_children }}</div>
        </div>
      @endif
      @if($buyer->company_name)
        <div class="control-group">
          <label class="control-label">Company Name</label>
          <div class="controls readonly">{{ $buyer->company_name }}</div>
        </div>
      @endif
      @if($buyer->position)
        <div class="control-group">
          <label class="control-label">Position</label>
          <div class="controls readonly">{{ $buyer->position }}</div>
        </div>
      @endif
      @if($buyer->company_address)
        <div class="control-group">
          <label class="control-label">Company Address</label>
          <div class="controls readonly">{{ $buyer->company_address }}</div>
        </div>
      @endif
    </div>
  </div> 
</div>