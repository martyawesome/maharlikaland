<h4><i>User's Account</i></h4>
<div class="box box-primary">
  <div class="box-body">
    <div class="form-group{{ $errors->has('Username') ? ' has-error' : '' }}">
      {!! Form::label('username', 'Username'); !!}
      {!! Form::text('username', $user->username, ['class' => 'form-control']) !!}
      {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <label>Email</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
        {!! Form::email('email', $user->email, ['class' => 'form-control'])!!}
      </div>   
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
      {!! Form::label('password', 'Password*'); !!}
      {!! Form::password('password', ['class'=>'form-control']) !!}
      {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
      {!! Form::label('password_confirmation', 'Confirm Password*'); !!}
      {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
      {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('user_type', 'User Type'); !!}
      {!! Form::select('user_type', $user_types, $user->user_type_id, ['class' => 'form-control', 'onchange' => 'onPropertyTypeChange(this)']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('Profile photo'); !!}
      {!! Form::file('image'); !!}
    </div>
    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
      {!! Form::label('first_name', 'First Name*'); !!}
      {!! Form::text('first_name', $user->first_name, ['class' => 'form-control']) !!}
      {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
      {!! Form::label('middle_name', 'Middle Name*'); !!}
      {!! Form::text('middle_name', $user->middle_name, ['class' => 'form-control']) !!}
      {!! $errors->first('middle_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
      {!! Form::label('last_name', 'Last Name*'); !!}
      {!! Form::text('last_name', $user->last_name, ['class' => 'form-control']) !!}
      {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
      {!! Form::label('nickname', 'Nickname'); !!}
      {!! Form::text('nickname', $user->nickname, ['class' => 'form-control']) !!}
      {!! $errors->first('nickname', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
      {!! Form::label('sex', 'Sex*'); !!}
      {!! Form::select('sex', array('Male' => 'Male', 'Female' => 'Female'), $user->sex, ['class' => 'form-control']); !!}
    </div>
    <!-- Date mm/dd/yyyy -->
    <div class="form-group {{ $errors->has('birthdate') ? ' has-error' : '' }}">
      {!! Form::label('birthdate', 'Birthdate (yyyy-mm-dd)*'); !!}
      {!! Form::text('birthdate', $user->birthdate, ['class' => 'form-control pull-right', 'style' => 'margin-bottom:10px;']) !!}
      {!! $errors->first('birthdate', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
      {!! Form::label('address', 'Address*'); !!}
      {!! Form::text('address', $user->address, ['class' => 'form-control']) !!}
      {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('contact_number') ? ' has-error' : '' }}">
      <label>Contact Number*</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          <input type="text" class="form-control" name="contact_number" value="{!! $user->contact_number !!}" data-inputmask='"mask": "09999999999"' data-mask>
      </div>
    </div>
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
    or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
      <div class="checkbox icheck">
          {!! Form::checkbox('is_admin_activated', null, $user->is_admin_activated, ['class' => 'flat-red']); !!}&nbsp;&nbsp;Is Admin Activated
      </div>
      <div class="form-group">
          {!! Form::checkbox('is_mobile_activated', null, $user->is_mobile_activated, ['class' => 'flat-red']); !!}&nbsp;&nbsp;Is Mobile Activated
      </div>
    @endif
  </div> 
</div>