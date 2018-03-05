<div class="box box-primary">
	<div class="box-body">
	  <div class="form-group{{ $errors->has('header') ? ' has-error' : '' }}">
	    {!! Form::label('header', 'Header*'); !!}
	    {!! Form::text('header', $about_me->header, ['class' => 'form-control']) !!}
	    {!! $errors->first('header', '<span class="help-block">:message</span>') !!}
	  </div>
	  <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
	    {!! Form::label('content', 'Content*'); !!}
	    {!! Form::textarea('content', $about_me->content, ['class' => 'form-control']) !!}
	    {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
	  </div>
	</div>
</div>