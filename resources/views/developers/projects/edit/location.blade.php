@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Location
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Location</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @include('includes.projects.create_update.location')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    window.onload = function() {
      onProvinceChange(document.getElementById("province"), <?php echo $cities_municipalities?>);

      $(document).ready(function () {
        $('select[name^="city_municipality"] option[value="'+<?php echo $project_location->city_municipality_id?>+'"]').attr("selected","selected");
      });
    };
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop