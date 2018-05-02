<div class="box box-success" >
  <div class="box-body" style="padding-top:0px;">
    <div class="control-group">
      <label class="control-label">Name</label>
      <div class="controls readonly">{{ $property->name }}</div>
    </div>
    <div class="control-group">
      <label class="control-label">Buyer</label>
      <div class="controls readonly">
        @if($property->buyer_name)
          {{ $property->buyer_name }}
        @else
          None
        @endif
      </div>
    </div>
    @if($property->property_type)
      <div class="control-group">
        <label class="control-label">Property Type</label>
        <div class="controls readonly">{{ $property->property_type }}</div>
      </div>
    @endif
    @if($property->joint_venture)
      <div class="control-group">
        <label class="control-label">Joint Venture</label>
        <div class="controls readonly">{{ $property->joint_venture }}</div>
      </div>
    @endif
    @if($property->price)
      <div class="control-group">
        <label class="control-label">Price</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($property->price, 2, '.', ',') }}</div>
      </div>
    @endif
    @if($property->price_per_sqm)
      <div class="control-group">
        <label class="control-label">Price per sqm</label>
        <div class="controls readonly"><?php echo config('constants.CURRENCY'); ?> {{ number_format($property->price_per_sqm, 2, '.', ',') }}</div>
      </div>
    @endif
    @if($property->property_status)
    <div class="control-group">
      <label class="control-label">Property Status</label>
      <div class="controls readonly">{{ $property->property_status }}</div>
    </div>
    @endif
  </div>
</div>
<div>
  <h2><small><b>Location</b></small></h2>
  <div class="box box-warning">
    <div class="box-body" style="padding-top:0px;">
      @if($property->province)
        <div class="control-group">
          <label class="control-label">Province</label>
          <div class="controls readonly">{{ $property->province }}</div>
        </div>
      @endif
      @if($property->city_municipality)
        <div class="control-group">
          <label class="control-label">City/Municipality</label>
          <div class="controls readonly">{{ $property->city_municipality }}</div>
        </div>
      @endif
      @if($property->barangay)
        <div class="control-group">
          <label class="control-label">Barangay</label>
          <div class="controls readonly">{{ $property->barangay }}</div>
        </div>
      @endif
      @if($property->street)
        <div class="control-group">
          <label class="control-label">Street</label>
          <div class="controls readonly">{{ $property->street }}</div>
        </div>
      @endif
      @if($property->block_number)
        <div class="control-group">
          <label class="control-label">Block Number</label>
          <div class="controls readonly">{{ $property->block_number }}</div>
        </div>
      @endif
      @if($property->lot_number)
        <div class="control-group">
          <label class="control-label">Lot Number</label>
          <div class="controls readonly">{{ $property->lot_number }}</div>
        </div>
      @endif
      @if($property->unit_number)
        <div class="control-group">
          <label class="control-label">Unit Number</label>
          <div class="controls readonly">{{ $property->unit_number }}</div>
        </div>
      @endif
      @if($property->coordinates)
        <div class="control-group">
          <label class="control-label">Coordinates</label>
          <div class="controls readonly">{{ $property->coordinates }}</div>
        </div>
      @endif
      @if($property->remarks)
        <div class="control-group">
          <label class="control-label">Remarks</label>
          <div class="controls readonly">{{ $property->remarks }}</div>
        </div>
      @endif
    </div>
  </div>
</div>
@if($property->floor)
  <div class="box box-default residential commercial-building">
    <div class="box-body" style="padding-top:0px;">
      <div class="control-group">
        <label class="control-label">Floors</label>
        <div class="controls readonly">{{ $property->floor }}</div>
      </div>
      @for($i = 0; $i < count($floor_areas); $i++)
        <div class="control-group">
          <label class="control-label">Floor {{$floor_areas[$i]->floor}} - Floor Area (sqm)</label>
          <div class="controls readonly">{{ number_format($floor_areas[$i]->floor_area, 2) }}</div>
        </div>
      @endfor
    </div>
  </div>
@endif
<h2><small><b>{!! $property->property_type !!}</b></small></h2>
<div class="box box-primary">
  <div class="box-body" style="padding-top:0px;">
    @if($property->lot_area)
      <div class="control-group">
        <label class="control-label">Lot Area (sqm)</label>
        <div class="controls readonly">{{ number_format($property->lot_area, 2) }}</div>
      </div>
    @endif
    @if($property->floor_area)
      <div class="control-group">
        <label class="control-label">Floor Area (sqm)</label>
        <div class="controls readonly">{{ number_format($property->floor_area, 2) }}</div>
      </div>
    @endif
    @if($property->bedrooms)
      <div class="control-group">
        <label class="control-label">Bedrooms</label>
        <div class="controls readonly">{{ $property->bedrooms }}</div>
      </div>
    @endif
    @if($property->bathrooms)
      <div class="control-group">
        <label class="control-label">Bathrooms</label>
        <div class="controls readonly">{{ $property->bathrooms }}</div>
      </div>
    @endif
    @if($property->is_furnished)
      <div class="control-group">
        <label class="control-label">Floors</label>
        @if($property->is_furnished)
          <div class="controls readonly">Yes</div>
        @else
          <div class="controls readonly">No</div>
        @endif
      </div>
    @endif
    @if($property->parking_availability)
      <div class="control-group">
        <label class="control-label">Parking availability</label>
        <div class="controls readonly">{{ $property->parking_availability }}</div>
      </div>
    @endif
  </div>
</div>


