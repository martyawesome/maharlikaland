@extends('agents.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      All Properties
      <small>Properties</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">All Properties</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($properties as $property)
            <a href="{{ URL::route('agent_view_property', $property->slug) }}">
              <li class="col-md-4 col-sm-6" style="list-style:none;">
                <div class="box-body box-profile">
                  <img class="img-responsive" src="<?php echo asset("").$property->main_picture_path?>" alt="User profile picture">
                  <h3 class="text-center"><i>{{ $property->name }}</i></h3>
                  <h4 class="text-center"><i>{{ $property->city_municipality }},{{ $property->province }}</i></h4>
                </div>
              </li>
            </a>
          @endforeach
        </ul>
      </div> 
    </div>
  </section>
@stop