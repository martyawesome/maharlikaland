@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Sources
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Sources</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @include('includes.projects.create_update.sources')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop