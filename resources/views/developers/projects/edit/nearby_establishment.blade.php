@extends('developers.base_dashboard')
@section('content')
  @include('modals.developers.delete_project_nearby_establishment')
  <section class="content-header">
    <h1>
      Nearby Establishments
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li>Nearby Establishments</li>
      <li class="active">{{ $nearby_establishment->nearby_establishment }}</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @include('includes.projects.create_update.nearby_establishments.edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop