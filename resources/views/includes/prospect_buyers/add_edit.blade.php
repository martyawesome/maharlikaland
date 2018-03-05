<h4><i>Prospect Buyer's Profile</i></h4>
<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
      {!! Form::label('first_name', 'First Name*'); !!}
      {!! Form::text('first_name', $prospect_buyer->first_name, ['class' => 'form-control']) !!}
      {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
      {!! Form::label('middle_name', 'Middle Name*'); !!}
      {!! Form::text('middle_name', $prospect_buyer->middle_name, ['class' => 'form-control']) !!}
      {!! $errors->first('middle_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
      {!! Form::label('last_name', 'Last Name*'); !!}
      {!! Form::text('last_name', $prospect_buyer->last_name, ['class' => 'form-control']) !!}
      {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('property', 'Property'); !!}
      {!! Form::select('property', $properties, $prospect_property->property_id, ['class' => 'form-control']); !!}
    </div>
    <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
      {!! Form::label('sex', 'Sex*'); !!}
      {!! Form::select('sex', array('Male' => 'Male', 'Female' => 'Female'), $prospect_buyer->sex, ['class' => 'form-control']); !!}
    </div>
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
      {!! Form::label('address', 'Address*'); !!}
      {!! Form::text('address', $prospect_buyer->address, ['class' => 'form-control']) !!}
      {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('contact_number') ? ' has-error' : '' }}">
      <label>Contact Number (Mobile)*</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          @if(old('contact_number'))
            <input type="text" class="form-control" name="contact_number" value="{!! old('contact_number') !!}" data-inputmask='"mask": "09999999999"' data-mask>
          @else
            @if($prospect_buyer->contact_number != null and $prospect_buyer->contact_number != "0000-00-00")
              <input type="text" class="form-control" name="contact_number" value="{!! $prospect_buyer->contact_number !!}" data-inputmask='"mask": "09999999999"' data-mask>
            @else
              <input type="text" class="form-control" name="contact_number" data-inputmask='"mask": "09999999999"' data-mask>
            @endif
          @endif
      </div>
      {!! $errors->first('contact_number_mobile', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <label>Email*</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
        {!! Form::email('email', $prospect_buyer->email, ['class' => 'form-control'])!!}
      </div>  
      {!! $errors->first('email', '<span class="help-block">:message</span>') !!} 
    </div>
  </div> 
</div>