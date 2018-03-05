@extends('developers.base_dashboard')
@section('content')
  @include('modals.developers.delete_project_joint_venture')
  <section class="content-header">
    <h1>
      Joint Venture
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li>Joint Ventures</li>
      <li class="active">{{ $joint_venture->name }}</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @include('includes.projects.create_update.joint_ventures.edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop