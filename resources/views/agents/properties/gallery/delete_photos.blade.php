@extends('agents.base_dashboard')
@section('content')
  @include('modals.agents.delete_property_photos')
  <script type="text/javascript">
    window.imageIds = [];
  </script>
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small>Property Gallery</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">All Properties</li>
      <li class="active">Gallery</li>
    </ol>
  </section>
  <section class="content">
    <div id="modalContainer"></div>
    <i>Select photos you want to delete</i>
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($gallery as $property_gallery)
            <li class="col-md-6 col-sm-6" style="list-style:none;" onclick="chooseImage(<?php echo $property_gallery->id?>)">
              <div class="box-body">
                <div class="box-body gallery-item">
                  <div class="selected-image-delete">
                    <img id="<?php echo $property_gallery->id?>" class="img-responsive gallery-image" src="<?php echo asset("").$property_gallery->image_path?>" alt="Gallery picture">
                  </div>
                  @if($property_gallery->image_path == $property->main_picture_path)
                    <p id="main-photo-identifier">Current main photo</p>
                  @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div> 
    </div>
    <div id="deletePropertyPhotosButton" class="btn btn-danger" disabled>Delete Photos</div></section>
  <script type="text/javascript">
    function chooseImage(imageId) {
      var idIsFound = false;
      for(var i = 0; i<window.imageIds.length; i++) {
        if(window.imageIds[i] == imageId) {
          idIsFound = true;
          break;
        }
      }
      if(idIsFound) {
        $('#' + imageId).css('opacity','1');
         window.imageIds = jQuery.grep(window.imageIds, function(value) {
          return value != imageId;
        });
      } else {
        $('#' + imageId).css('opacity','0.5');
        window.imageIds.push(imageId);
      }
      if(window.imageIds.length == 0) {
        $("#deletePropertyPhotosButton").attr("disabled", true);
        $("#deletePropertyPhotosButton").off("click");
      } else {
        $("#deletePropertyPhotosButton").attr("disabled", false);
        $("#deletePropertyPhotosButton").click(function(){
          $('#deletePropertyPhotosModal').modal('show');
        });
        $("#closeDeletePropertyPhotosButton").click(function(){
          $('#deletePropertyPhotosModal').modal('hide');
        });
        $("#deletePropertyPhotosModalButton").click(function() {
          var url = "{{ URL::route('delete_property_photos',[$property->slug,':ids']) }}";
          var photoIds = btoa(JSON.stringify(window.imageIds));
          url = url.replace(':ids', photoIds);
          window.location = url;
        });
      }
    }
  </script>
@stop