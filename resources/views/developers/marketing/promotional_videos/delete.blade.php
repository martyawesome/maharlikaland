@extends('developers.base_dashboard')
@section('content')
  @include('modals.delete_videos')
  <script type="text/javascript">
    window.objectIds = [];
  </script>
  <section class="content-header">
    <h1>
      Delete Promotional Videos
    </h1>
    <ol class="breadcrumb">
      <li>Marketing</li>
      <li>Promotional Videos</li>
      <li class="active">Delete</li>
    </ol>
  </section>
  <section class="content">
    <div id="modalContainer"></div>
    <h4><i>Click the videos you want to delete</i></h4>
    <div class="box box-primary">
      <div class="box-body">
        <ul class="row list-group">
          @foreach($promotional_videos as $promotional_video)
            <li class="col-md-12 col-sm-12" id="<?php echo $promotional_video->id?>" style="list-style:none; background-color:#ddd; margin-bottom:25px;" onclick="chooseObject(<?php echo $promotional_video->id?>)">
              <div class="box-body gallery-item">
                <video width="100%" controls>
                  <source src="<?php echo asset("").$promotional_video->file_path?>">
                </video>
                @if($promotional_video->extension != "mp4")
                  <p>Your browser does not support {{ $promotional_video->extension }} format.</p>
                @endif
                {{ $promotional_video->project_name }}
              </div>
            </li>
          @endforeach
        </ul>
      </div> 
    </div>
    <div id="delete-objects-button" class="btn btn-danger" disabled>Delete Videos</div>
  </section>
  @include('modals.delete')
  <script type="text/javascript" src="{{ URL::asset('js/delete_promotional_videos.js') }}"></script>
@stop