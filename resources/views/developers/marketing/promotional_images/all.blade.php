@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Promotional Images
    </h1>
    <ol class="breadcrumb">
      <li>Marketing</li>
      <li class="active">Promotional Images</li>
    </ol>
  </section>
  <section class="content">
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
    or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
      <div class="box box-primary">
        <div class="box-body">
          <a href="{{ route('promotional_images_projects') }}" class="btn btn-success" style="margin-right: 5px;">Upload</a>
          @if(count($promotional_images) > 0)
            <a href="{{ route('show_delete_promotional_images') }}" class="btn btn-danger">Delete</a>
          @endif
        </div>
      </div>
    @endif
    <div class="box box-primary">
      <div class="box-body">
        @if(count($promotional_images) > 0)
          @foreach($promotional_images as $promotional_image)
            <li class="col-md-6 col-sm-12" style="list-style:none;">
              <div class="box-body gallery-item">
                  <img id="<?php echo $promotional_image->id?>" class="img-responsive" src="<?php echo asset("").$promotional_image->file_path?>" alt="Promotional image">                    
                  <p id="main-photo-identifier">{{ $promotional_image->project_name }}</p>
              </div>
            </li>
          @endforeach
        @else
          No promotional images found
        @endif
      </div>
    </div>
  </section>
@stop