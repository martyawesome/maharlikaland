@extends('developers.base_dashboard')
@section('content')
  @include('modals.select_main_photo')
  <script type="text/javascript">
    window.imageId = '';
  </script>
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li>Properties</li>
      <li>Gallery</li>
      <li class="active">Main Photo</li>
    </ol>
  </section>
  <section class="content">
    <div id="modalContainer"></div>
    <i>Select your desired main photo</i>
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($gallery as $property_gallery)
            <li class="col-md-6 col-sm-6" style="list-style:none;" onclick="chooseImage(<?php echo $property_gallery->id?>)">
              <div class="box-body gallery-item">
                  <div class="selected-image-main-photo">
                    <img id="<?php echo $property_gallery->id?>" class="img-responsive" src="<?php echo asset("").$property_gallery->image_path?>" alt="Gallery picture">
                    @if($property_gallery->image_path == $property->main_picture_path)
                      <p id="main-photo-identifier">Current Main Photo</p>
                    @endif
                  </div> 
              </div>
            </li>
          @endforeach
        </ul>
      </div> 
    </div>
    <div id="selectMainPhotoButton" class="btn btn-primary" disabled>Select Main Photo</div></section>
  <script type="text/javascript">
    function chooseImage(imageId) {
      
      var hasAlreadySelectedMainPhoto = false;
      var hasDeselectedPrevious = false;

      if(window.imageId != null) {
        hasAlreadySelectedMainPhoto = true;
      }

      if(window.imageId == imageId) {
        hasDeselectedPrevious = true;
      }

      if(hasAlreadySelectedMainPhoto && !hasDeselectedPrevious) {
        $('#' + window.imageId).css('opacity','1');
        $('#' + imageId).css('opacity','0.5');
      } else if(hasDeselectedPrevious){
         $('#' +imageId).css('opacity','1');
      } else {
         $('#' +imageId).css('opacity','0.5');
      }

      if(window.imageId == imageId) {
        window.imageId = '';
      } else {
        window.imageId = imageId;
      }

      if(window.imageId == '') {
        $("#selectMainPhotoButton").attr("disabled", true);
        $("#selectMainPhotoButton").off("click");
      } else {
        $("#selectMainPhotoButton").attr("disabled", false);
        $("#selectMainPhotoButton").click(function(){
          $('#selectMainPhotoModal').modal('show');
        });
        $("#closeMainPhotoModalButton").click(function(){
          $('#selectMainPhotoModal').modal('hide');
        });
        $("#selectMainPhotoModalButton").click(function() {
          var url = "{{ URL::route('choose_property_main_photo',array($project->slug, $property->slug,':id')) }}";
          url = url.replace(':id', window.imageId);
          window.location = url;
        });
      }
    }
  </script>
@stop