<h4><i>Developer's Details</i></h4>
<div class="box box-success">
  <div class="box-body">
    <div class="form-group{{ $errors->has('developer_name') ? ' has-error' : '' }}">
      {!! Form::label('developer_name', 'Name*'); !!}
      {!! Form::text('developer_name', $developer->name, ['class' => 'form-control']) !!}
      {!! $errors->first('developer_name', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
      {!! Form::label('Logo'); !!}
      {!! Form::file('logo'); !!}
    </div>
    <div class="form-group">
      {!! Form::label('Header Image'); !!}
      {!! Form::file('header_image'); !!}
    </div>
    <div class="form-group">
      {!! Form::label('Banner'); !!}
      {!! Form::file('banner'); !!}
    </div>
    <div class="form-group{{ $errors->has('overview') ? ' has-error' : '' }}">
      {!! Form::label('overview', 'Overview'); !!}
      {!! Form::textarea('overview', $developer->overview, ['class' => 'form-control']) !!}
      {!! $errors->first('overview', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('mission') ? ' has-error' : '' }}">
      {!! Form::label('mission', 'Mission'); !!}
      {!! Form::textarea('mission', $developer->mission, ['class' => 'form-control']) !!}
      {!! $errors->first('mission', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('vission') ? ' has-error' : '' }}">
      {!! Form::label('vision', 'Vision'); !!}
      {!! Form::textarea('vision', $developer->vision, ['class' => 'form-control']) !!}
      {!! $errors->first('vision', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('developer_address') ? ' has-error' : '' }}">
      {!! Form::label('developer_address', 'Address'); !!}
      {!! Form::text('developer_address', $developer->address, ['class' => 'form-control']) !!}
      {!! $errors->first('developer_address', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('coordinates') ? ' has-error' : '' }}">
      {!! Form::label('coordinates', 'Coordinates'); !!}
      {!! Form::text('coordinates', $developer->coordinates, ['class' => 'form-control']) !!}
      {!! $errors->first('coordinates', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('developer_contact_number') ? ' has-error' : '' }}">
      <label>Contact Number</label>
      <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-phone"></i>
          </div>
          <input type="text" class="form-control" name="developer_contact_number" value="{!! $developer->contact_number !!}" data-inputmask='"mask": "09999999999"' data-mask>
      </div>
    </div>
    <div class="form-group{{ $errors->has('developer_email') ? ' has-error' : '' }}">
      <label>Email</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
        {!! Form::email('developer_email', $developer->email, ['class' => 'form-control'])!!}
      </div>
    </div>
    <div class="form-group{{ $errors->has('website_url') ? ' has-error' : '' }}">
      {!! Form::label('website_url',  'Website URL'); !!}
      {!! Form::text('website_url', $developer->website_url, ['class' => 'form-control']) !!}
      {!! $errors->first('website_url', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('facebook_url') ? ' has-error' : '' }}">
      {!! Form::label('facebook_url',  'Facebook URL'); !!}
      {!! Form::text('facebook_url', $developer->facebook_url, ['class' => 'form-control']) !!}
      {!! $errors->first('facebook_url', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('twitter_url') ? ' has-error' : '' }}">
      {!! Form::label('twitter_url', 'Twitter URL'); !!}
      {!! Form::text('twitter_url', $developer->twitter_url, ['class' => 'form-control']) !!}
      {!! $errors->first('twitter_url', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('linkdin_url') ? ' has-error' : '' }}">
      {!! Form::label('linkedin_url', 'LinkedIn URL'); !!}
      {!! Form::text('linkedin_url', $developer->linkedin_url, ['class' => 'form-control']) !!}
      {!! $errors->first('linkedin_url', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group">
        {!! Form::checkbox('is_developer_activated', null, $developer->is_activated, ['class' => 'flat-red']); !!} Is Developer Activated
    </div>
    <div class="form-group{{ $errors->has('security_code') ? ' has-error' : '' }}">
        {!! Form::label('security_code', 'Security Code*'); !!}
        {!! Form::password('security_code', ['class'=>'form-control']) !!}
        {!! $errors->first('security_code', '<span class="help-block">:message</span>') !!}
    </div>
    <div class="form-group{{ $errors->has('security_code_confirmation') ? ' has-error' : '' }}">
        {!! Form::label('security_code_confirmation', 'Confirm Security Code*'); !!}
        {!! Form::password('security_code_confirmation', ['class'=>'form-control']) !!}
        {!! $errors->first('security_code_confirmation', '<span class="help-block">:message</span>') !!}
    </div>
  </div> 
</div>