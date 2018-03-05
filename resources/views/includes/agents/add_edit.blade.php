<div class="box box-warning">
	<div class="box-body">
	  <div class="form-group{{ $errors->has('prc_license_number') ? ' has-error' : '' }}">
	    {!! Form::label('prc_license_number', 'PRC License Number'); !!}
	    {!! Form::text('prc_license_number', $agent->prc_license_number, ['class' => 'form-control']) !!}
	    {!! $errors->first('prc_license_number', '<span class="help-block">:message</span>') !!}
	  </div>
	  <div class="form-group">
	    {!! Form::label('facebook_url', 'Facebook URL'); !!}
	    {!! Form::text('facebook_url', $agent->facebook_url, ['class' => 'form-control']) !!}
	  </div>
	  <div class="form-group">
	    {!! Form::label('twitter_url', 'Twitter URL'); !!}
	    {!! Form::text('twitter_url', $agent->twitter_url, ['class' => 'form-control']) !!}
	  </div>
	  <div class="form-group">
	    {!! Form::label('linkdin_url', 'LinkedIn URL'); !!}
	    {!! Form::text('linkdin_url', $agent->linkdin_url, ['class' => 'form-control']) !!}
	  </div>
	</div>
</div>