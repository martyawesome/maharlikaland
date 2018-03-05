@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Upload Photos for {{ $project->name }}
      <small><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li>Gallery</li>
      <li class="active">Upload</li>
    </ol>
  </section>
  <section class="content">
     @include('includes.projects.upload_project_photos')
  </section>
@stop