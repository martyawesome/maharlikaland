@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Gallery
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li class="active"><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
    </ol>
  </section>
  <section class="content">
    @include('includes.projects.view.gallery_items')
    {!! link_to_route('project_upload_images', 'Upload Photos', array($project->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
    {!! link_to_route('show_delete_project_images', 'Delete Photos', array($project->slug), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
  </section>
@stop