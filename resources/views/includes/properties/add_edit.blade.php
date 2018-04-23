<div class="box box-success">
	<div class="box-body">
		<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			{!! Form::label('name', 'Name*'); !!}
			{!! Form::text('name', $property->name, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
			{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
		</div>
		@if($property->project_id != null and count($joint_ventures) > 0)
			<div class="form-group">
				{!! Form::label('joint_venture', 'Joint Venture'); !!}
				{!! Form::select('joint_venture', $joint_ventures, $property->joint_venture_id, ['class' => 'form-control']) !!}
			</div>
		@endif
		<div class="form-group">
			{!! Form::label('property_type', 'Property Type'); !!}
			{!! Form::select('property_type', $property_types, $property->property_type_id, ['class' => 'form-control', 'onchange' => 'onPropertyTypeChange(this)']) !!}
		</div>
		<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
			{!! Form::label('price', 'Price'); !!}
			{!! Form::text('price', $property->price, ['class' => 'form-control', 'placeholder' => 'Price']) !!}
			{!! $errors->first('price', '<span class="help-block">:message</span>') !!}
		</div>
		<div class="form-group{{ $errors->has('price_per_sqm') ? ' has-error' : '' }}">
			{!! Form::label('price_per_sqm', 'Price per sqm'); !!}
			{!! Form::text('price_per_sqm', $property->price_per_sqm, ['class' => 'form-control', 'placeholder' => 'Price per sqm']) !!}
			{!! $errors->first('price_per_sqm', '<span class="help-block">:message</span>') !!}
		</div>
		@if(count($buyers) > 0)
			@if($property->buyer_id)
				<div class="form-group">
					{!! Form::label('buyer', 'Buyer'); !!}
					{!! Form::select('buyer', $buyers, $property->buyer_id, ['class' => 'form-control']) !!}
				</div>
			@else
				<div class="form-group">
					{!! Form::label('buyer', 'Buyer'); !!}
					{!! Form::select('buyer', $buyers, null, ['class' => 'form-control']) !!}
				</div>
			@endif
		@endif
		@if($property->agent_id)
			<div class="form-group">
				{!! Form::label('agent', 'Agent'); !!}
				{!! Form::select('agent', $agents, $property->agent_id, ['class' => 'form-control']) !!}
			</div>
		@else
			<div class="form-group">
				{!! Form::label('agent', 'Agent'); !!}
				{!! Form::select('agent', $agents, null, ['class' => 'form-control']) !!}
			</div>
		@endif
		<div class="form-group">
			{!! Form::label('property_status', 'Property Status'); !!}
			{!! Form::select('property_status', $property_statuses, $property->property_status_id, ['class' => 'form-control']) !!}
		</div>
		<!-- <div class="checkbox icheck">
			@if($property->id)
				{!! Form::checkbox('is_active',"yes",$property->is_active) !!}
			@else
				{!! Form::checkbox('is_active',"yes",1) !!}
			@endif
			&nbsp;&nbsp;Is Active
		</div> -->
	</div>
</div>

<div>
	<h2><small><b>Location</b></small></h2>
	<div class="box box-warning">
		<div class="box-body">
			<div class="form-group">
				{!! Form::label('province', 'Province'); !!}
				{!! Form::select('province', $provinces_list, $property_location->province_id, ['class' => 'form-control', 'onchange' => 'onProvinceChange(this,'.$cities_municipalities.')']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('city_municipality', 'City/Municipality'); !!}
				{!! Form::select('city_municipality', [], $property_location->city_municipality_id, ['class' => 'form-control']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('barangay', 'Barangay'); !!}
				{!! Form::text('barangay', $property_location->barangay, ['class' => 'form-control']) !!}
			</div>
			<div class="form-group">
				{!! Form::label('street', 'Street'); !!}
				{!! Form::text('street', $property_location->street, ['class' => 'form-control']) !!}
			</div>
			<div class="residential lot">
				<div class="form-group{{ $errors->has('block_number') ? ' has-error' : '' }}">
					{!! Form::label('block_number', 'Block Number'); !!}
					{!! Form::text('block_number', $property_location->block_number, ['class' => 'form-control']) !!}
					{!! $errors->first('block_number', '<span class="help-block">:message</span>') !!}
				</div>
				<div class="form-group{{ $errors->has('lot_number') ? ' has-error' : '' }}">
					{!! Form::label('lot_number', 'Lot Number'); !!}
					{!! Form::text('lot_number', $property_location->lot_number, ['class' => 'form-control']) !!}
					{!! $errors->first('lot_number', '<span class="help-block">:message</span>') !!}
				</div>
			</div>
			<div class="form-group{{ $errors->has('unit_number') ? ' has-error' : '' }} condominium-unit" style="display: none;">
				{!! Form::label('unit_number', 'Unit Number'); !!}
				{!! Form::text('unit_number', $property_location->unit_number, ['class' => 'form-control']) !!}
				{!! $errors->first('unit_number', '<span class="help-block">:message</span>') !!}
			</div>
			<div class="form-group{{ $errors->has('coordinates') ? ' has-error' : '' }}">
				{!! Form::label('coordinates', 'Coordinates'); !!} <a href="http://www.maps.google.com" target="_blank">&nbsp;(Go to Google Maps)</a>
				{!! Form::text('coordinates', $property_location->coordinates, ['class' => 'form-control']) !!}
				{!! $errors->first('coordinates', '<span class="help-block">:message</span>') !!}
			</div>
			<div class="form-group">
				{!! Form::label('remarks', 'Remarks'); !!}
				{!! Form::textarea('remarks', $property_location->remarks, ['class' => 'form-control', 'rows' => '3']); !!}
			</div>
		</div>
	</div>
</div>

<div class="box box-default residential commercial-building">
	<div class="box-body">
		<div class="form-group">
			{!! Form::label('floors', 'Floors'); !!}
			{!! Form::select('floors', $floors, $property->floor_id, ['class' => 'form-control', 'onchange' => 'onFloorsChange(this,'.count($floors).')']) !!}
		</div>
		<div id="floors_container">
			@for($i = 1; $i <= count($floors); $i++)
				<div class="form-group{{ $errors->first('floor_area_per_floor.'.$i, ' has-error') }}" id="floor_area_per_floor[{{$i}}]">
					{!! Form::label('floor_area_per_floor['.$i.']', 'Floor Area (sqm)*'); !!}
					{!! Form::text('floor_area_per_floor['.$i.']', null, ['class' => 'form-control', 'placeholder' => 'Floor '.$i]) !!}
					{!! $errors->first('floor_area_per_floor.'.$i, '<span class="help-block">Invalid floor area</span>') !!}
				</div>
			@endfor
		</div>
	</div>
</div>	

<h2 class="residential"><small><b>Residential</b></small></h2>
<h2 class="lot" style="display: none;"><small><b>Lot</b></small></h2>
<h2 class="condominium-unit" style="display: none;"><small><b>Condominium Unit</b></small></h2>
<h2 class="commercial-unit" style="display: none;"><small><b>Commercial Unit</b></small></h2>
<h2 class="commercial-building" style="display: none;"><small><b>Commercial Building</b></small></h2>

<div class="box box-primary">
	<div class="box-body">
		<div class="form-group{{ $errors->has('lot_area') ? ' has-error' : '' }} residential lot commercial-building">
			{!! Form::label('lot_area', 'Lot Area*'); !!}
			{!! Form::text('lot_area', $property->lot_area, ['class' => 'form-control']) !!}
			{!! $errors->first('lot_area', '<span class="help-block">:message</span>') !!}
		</div>
		<div class="form-group{{ $errors->has('floor_area') ? ' has-error' : '' }} residential commercial-unit condominium-unit" style="display: none;">
			{!! Form::label('floor_area', 'Floor Area'); !!}
			{!! Form::text('floor_area', $property->floor_area, ['class' => 'form-control']) !!}
			{!! $errors->first('floor_area', '<span class="help-block">:message</span>') !!}
		</div>
		<div class="form-group residential condominium-unit">
			{!! Form::label('number_of_bedrooms', 'Number of Bedrooms'); !!}
			{!! Form::select('number_of_bedrooms', $number_of_bedrooms, $property->number_of_bedrooms_id, ['class' => 'form-control']) !!}
		</div>
		<div class="form-group residential condominium-unit">
			{!! Form::label('number_of_bathrooms', 'Number of Bathrooms'); !!}
			{!! Form::select('number_of_bathrooms', $number_of_bathrooms, $property->number_of_bathrooms_id, ['class' => 'form-control']) !!}
		</div>
		<div class="checkbox icheck residential commercial-unit condominium-unit">
          {!! Form::checkbox('furnished',"yes",$property->is_furnished) !!}
          &nbsp;&nbsp;Is Furnished
        </div>
        <div class="form-group residential condominium-unit commercial-unit commercial-building">
			{!! Form::label('parking_availability', 'Parking Availability'); !!}
			{!! Form::select('parking_availability', $parking_availability, $property->parking_availability_id, ['class' => 'form-control']) !!}
		</div>
	</div>
</div>
