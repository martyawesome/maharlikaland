@extends('developers.base_dashboard')
@section('content')
  @include('modals.developers.properties.delete')
  @include('modals.success')
  @include('modals.modal')
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Project</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li>Properties</li>
      <li class="active">Gallery</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @if(count($gallery)==0)
            <div class="box-body">
              No photos found
            </div>
          @else
            @foreach($gallery as $property_gallery)
              <li class="col-md-6 col-sm-6" style="list-style:none;">
                <div class="box-body gallery-item">
                    <img id="<?php echo $property_gallery->id?>" class="img-responsive" src="<?php echo asset("").$property_gallery->image_path?>" alt="Gallery picture">                    
                    @if($property_gallery->image_path == $property->main_picture_path)
                      <p id="main-photo-identifier">Main photo</p>
                    @endif
                </div>
              </li>
            @endforeach
          @endif
        </ul>
      </div> 
    </div>
    {!! link_to_route('show_choose_property_main_photo', 'Choose Main Photo', array($project->slug, $property->slug), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    {!! link_to_route('property_upload_images', 'Upload Photos', array($project->slug, $property->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
    {!! link_to_route('show_delete_property_photos', 'Delete Photos', array($project->slug, $property->slug), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
 @stop