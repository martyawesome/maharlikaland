<div>
  <h2 style="margin-top:0px;"><small><b>Basic Information</b></small></h2>
  <div class="box box-success">
    <div class="box-body">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name*'); !!}
        {!! Form::text('name', $project->name, ['class' => 'form-control']) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
      </div>
      <div class="form-group">
        {!! Form::label('project_type', 'Project Type'); !!}
        {!! Form::select('project_type', $project_type_list, $project->project_type_id, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('opening_date', 'Opening Date'); !!}
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          @if($project->opening_date != null and $project->opening_date != "0000-00-00")
            <input type="text" name="opening_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" value="{!! $project->opening_date !!}" data-mask>
          @else
            <input type="text" name="opening_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('development_date', 'Development Date'); !!}
        <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          @if($project->deveplopment_date != null and $project->deveplopment_date != "0000-00-00")
            <input type="text" name="development_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" value="{!! $project->development_date !!}" data-mask>
          @else
            <input type="text" name="development_date" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
          @endif
        </div>
      </div>
      <div class="checkbox icheck">
        {!! Form::checkbox('is_preselling',"yes",$project->is_preselling) !!}
        &nbsp;&nbsp;Is Pre-selling
      </div>
      <div class="checkbox icheck">
        {!! Form::checkbox('is_active',"yes",$project->is_active) !!}
        &nbsp;&nbsp;Is Active
      </div>
      <div class="form-group">
        {!! Form::label('overview', 'Overview'); !!}
        {!! Form::textarea('overview', $project->overview, ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('Logo'); !!}
        {!! Form::file('logo'); !!}
      </div>
      <div class="form-group">
        {!! Form::label('Banner'); !!}
        {!! Form::file('banner'); !!}
      </div>
    </div>
  </div>
</div>