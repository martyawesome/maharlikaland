
<h4><i>Buyer's Profile</i></h4>
<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
      {!! Form::label('first_name', 'First Name*'); !!}
      {!! Form::text('first_name', $buyer->first_name, ['class' => 'form-control']) !!}
      {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
      {!! Form::label('middle_name', 'Middle Name*'); !!}
      {!! Form::text('middle_name', $buyer->middle_name, ['class' => 'form-control']) !!}
      {!! $errors->first('middle_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
      {!! Form::label('last_name', 'Last Name*'); !!}
      {!! Form::text('last_name', $buyer->last_name, ['class' => 'form-control']) !!}
      {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
      {!! Form::label('sex', 'Sex*'); !!}
      {!! Form::select('sex', array('Male' => 'Male', 'Female' => 'Female'), $buyer->sex, ['class' => 'form-control']); !!}
    </div>
    <div class="form-group {{ $errors->has('birthdate') ? ' has-error' : '' }}">
      {!! Form::label('birthdate', 'Birthdate'); !!}
      <div class="input-group">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        @if(old('birthdate'))
          <input type="text" name="birthdate" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! old('birthdate') !!}" data-mask>
        @else
          @if($buyer->birthdate != null and $buyer->birthdate != "0000-00-00")
            <input type="text" name="birthdate" class="form-control" data-inputmask="'alias': 'yyyy/mm/dd'" value="{!! $buyer->birthdate !!}" data-mask>
          @else
            <input type="text" name="birthdate" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        @endif
       </div>
    </div>
    <div class="form-group{{ $errors->has('home_address') ? ' has-error' : '' }}">
      {!! Form::label('home_address', 'Home Address*'); !!}
      {!! Form::text('home_address', $buyer->home_address, ['class' => 'form-control']) !!}
      {!! $errors->first('home_address', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('contact_number_mobile') ? ' has-error' : '' }}">
      <label>Contact Number* (Mobile)</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          @if(old('contact_number_mobile'))
            <input type="text" class="form-control" name="contact_number_mobile" value="{!! old('contact_number_mobile') !!}" data-inputmask='"mask": "09999999999"' data-mask>
          @else
            @if($buyer->contact_number_mobile != null and $buyer->contact_number_mobile != "0000-00-00")
              <input type="text" class="form-control" name="contact_number_mobile" value="{!! $buyer->contact_number_mobile !!}" data-inputmask='"mask": "09999999999"' data-mask>
            @else
              <input type="text" class="form-control" name="contact_number_mobile" data-inputmask='"mask": "09999999999"' data-mask>
            @endif
          @endif
      </div>
      {!! $errors->first('contact_number_mobile', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('contact_number_home') ? ' has-error' : '' }}">
      <label>Contact Number (Home)</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          @if(old('contact_number_home'))
            <input type="text" class="form-control" name="contact_number_home" value="{!! old('contact_number_home') !!}" data-inputmask='"mask": "(99) 999-9999"' data-mask>
          @else
            @if($buyer->contact_number_home != null and $buyer->contact_number_home != "0000-00-00")
              <input type="text" class="form-control" name="contact_number_home" value="{!! $buyer->contact_number_home !!}" data-inputmask='"mask": "(99) 999-9999"' data-mask>
            @else
              <input type="text" class="form-control" name="contact_number_home" data-inputmask='"mask": "(99) 999-9999"' data-mask>
            @endif
          @endif</div>
      {!! $errors->first('contact_number_home', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('contact_number_office') ? ' has-error' : '' }}">
      <label>Contact Number (Office)</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          @if(old('contact_number_office'))
            <input type="text" class="form-control" name="contact_number_office" value="{!! old('contact_number_office') !!}" data-inputmask='"mask": "(99) 999-9999"' data-mask>
          @else
            @if($buyer->contact_number_office != null and $buyer->contact_number_office != "0000-00-00")
              <input type="text" class="form-control" name="contact_number_office" value="{!! $buyer->contact_number_office !!}" data-inputmask='"mask": "(99) 999-9999"' data-mask>
            @else
              <input type="text" class="form-control" name="contact_number_office" data-inputmask='"mask": "(99) 999-9999"' data-mask>
            @endif
          @endif
      </div>
      {!! $errors->first('contact_number_office', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <label>Email</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
        {!! Form::email('email', $buyer->email, ['class' => 'form-control'])!!}
      </div>  
      {!! $errors->first('email', '<span class="help-block">:message</span>') !!} 
    </div>
    <div class="form-group">
      {!! Form::label('civil_status', 'Civil Status'); !!}
      {!! Form::text('civil_status', $buyer->civil_status, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('spouse_name', 'Spouse Name'); !!}
      {!! Form::text('spouse_name', $buyer->spouse_name, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group{{ $errors->has('num_of_children') ? ' has-error' : '' }}">
      {!! Form::label('num_of_children', 'Number of Children'); !!}
      {!! Form::text('num_of_children', $buyer->num_of_children, ['class' => 'form-control']) !!}
      {!! $errors->first('num_of_children', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('company_name', 'Company Name'); !!}
      {!! Form::text('company_name', $buyer->company_name, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('position', 'Position'); !!}
      {!! Form::text('position', $buyer->position, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('company_address', 'Company Address'); !!}
      {!! Form::text('company_address', $buyer->company_address, ['class' => 'form-control']) !!}
    </div>
  </div> 
</div>