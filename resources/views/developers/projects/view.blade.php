@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.delete_project')
  <section class="content-header">
    <h1>
      {{ $project->name }}
      <small>{{$project->city_municipality}},{{$project->province}}</small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li class="active">{{ $project->name }}</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-success">
        <div class="project-list-photo-container">
          <img class="img-responsive" src="<?php echo asset("").$project->logo_path?>" >
        </div>
    </div>
    @include('includes.projects.view.basic_info') 
    @include('includes.projects.view.location')
    @include('includes.projects.view.blocks')
    @include('includes.projects.view.joint_ventures')
    @include('includes.projects.view.sources')
    @include('includes.projects.view.amenities')
    @include('includes.projects.view.nearby_establishments')
    @include('includes.projects.view.incentives')
    @include('includes.projects.view.subdivision_plan')
    @include('includes.projects.view.vicinity_map')
    @include('includes.projects.view.gallery')
    
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
      <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
    @endif
  </section>
@stop