@extends('agents.base_dashboard')
@section('content')
  @include('modals.delete_agent_property')
  @include('modals.success')
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small>Gallery</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Project</li>
      <li class="active">Properties</li>
      <li class="active">Gallery</li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($gallery as $property_gallery)
            <li class="col-md-6 col-sm-6" style="list-style:none;" onclick="chooseImage(<?php echo $property_gallery->id?>)">
              <div class="box-body gallery-item">
                  <img id="<?php echo $property_gallery->id?>" class="img-responsive" src="<?php echo asset("").$property_gallery->image_path?>" alt="Gallery picture">                    
                  @if($property_gallery->image_path == $property->main_picture_path)
                    <p id="main-photo-identifier">Main photo</p>
                  @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div> 
    </div>
    {!! link_to_route('show_choose_property_main_photo', 'Choose Main Photo', array($property->slug), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
    {!! link_to_route('property_upload_images', 'Upload Photos', [$property->slug], ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
    {!! link_to_route('show_delete_property_photos', 'Delete Photos', array($property->slug), ['class' => 'btn btn-danger', 'style' => 'margin-right:5px;']) !!}
@stop