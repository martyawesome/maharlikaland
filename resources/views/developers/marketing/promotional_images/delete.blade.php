@extends('developers.base_dashboard')
@section('content')
  @include('modals.delete_photos')
  <script type="text/javascript">
    window.objectIds = [];
  </script>
  <section class="content-header">
    <h1>
      Delete Promotional Images
    </h1>
    <ol class="breadcrumb">
      <li>Marketing</li>
      <li>Promotional Images</li>
      <li class="active">Delete</li>
    </ol>
  </section>
  <section class="content">
    <div id="modalContainer"></div>
    <i>Select photos you want to delete</i>
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($promotional_images as $promotional_image)
            <li id="<?php echo $promotional_image->id?>" class="col-md-6 col-sm-12" style="list-style:none; margin-bottom:25px;" onclick="chooseObject(<?php echo $promotional_image->id?>)">
              <div class="box-body">
                <div class="box-body gallery-item">
                  <div class="selected-image-delete">
                    <img class="img-responsive gallery-image" src="<?php echo asset("").$promotional_image->file_path?>" alt="Promotional image">
                    <p id="main-photo-identifier">{{ $promotional_image->project_name }}</p>
                  </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div> 
    </div>
    <div id="delete-objects-button" class="btn btn-danger" disabled>Delete Images</div>
  </section>
  @include('modals.delete')
  <script type="text/javascript" src="{{ URL::asset('js/delete_promotional_images.js') }}"></script>
@stop