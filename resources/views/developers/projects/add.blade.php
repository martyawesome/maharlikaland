@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Project
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      @include('includes.projects.create_update.basic_info')
      @include('includes.projects.create_update.location')
      @include('includes.projects.create_update.lots_blocks')
      @include('includes.projects.create_update.sources')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript">
    window.onload = function() {
      onProvinceChange(document.getElementById("province"), <?php echo $cities_municipalities?>);
    };
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop