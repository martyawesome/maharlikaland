@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Vicinity Map
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Vicinity Map</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @if($vicnity_map_project)
        <div class="box box-primary">
          <div class="box-body">
            <b>Current:</b>
            <div class="box-body gallery-item">
                <div class="selected-image-main-photo">
                  <img class="img-responsive" src="<?php echo asset("").$vicnity_map_project->image_path?>" alt="Gallery picture">
                </div> 
            </div>
          </div> 
        </div>
      @endif
      @include('includes.projects.create_update.vicinity_map')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>
@stop