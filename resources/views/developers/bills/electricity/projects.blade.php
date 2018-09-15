@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Electricity Bills
      <small>Projects</small>
    </h1>
    <ol class="breadcrumb">
      <li>Bills</li>
      <li class="active">Electricity</li>
    </ol>
  </section>
  <section class="content">
    <div class="box-body">
      <ul class="row list-group">
        @if(count($projects) > 0)
          @foreach($projects as $project)
            <a href="{{ URL::route('bills_electricity_project',$project->slug) }}" class="project-list-container">
              <li style="list-style:none;">
                <div class="box box-success">
                    <div class="project-list-photo-container">
                      <img class="img-responsive" src="<?php echo asset("").$project->logo_path?>" >
                    </div>
                    <div class="project-list-text-overlay">
                      <h1>{{ $project->name }}</h1>
                      <h4>{{ $project->city_municipality }}, {{ $project->province }}</h4>
                    </div>
                </div>
              </li>
            </a>
          @endforeach
        @else
          No projects found
        @endif
      </ul>
    </div>
  </section>
@stop