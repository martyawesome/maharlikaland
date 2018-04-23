@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Promotional Videos
    </h1>
    <ol class="breadcrumb">
      <li>Marketing</li>
      <li class="active">Promotional Videos</li>
    </ol>
  </section>
  <section class="content">
    @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
    or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
      <div class="box box-primary">
        <div class="box-body">
          <a href="{{ route('promotional_videos_projects') }}" class="btn btn-success" style="margin-right: 5px;">Upload</a>
          @if(count($promotional_videos) > 0)
            <a href="{{ route('show_delete_promotional_videos') }}" class="btn btn-danger">Delete</a>
          @endif
        </div>
      </div>
    @endif
    <div class="box box-primary">
      <div class="box-body">
        @if(count($promotional_videos) > 0)
          @foreach($promotional_videos as $promotional_video)
            <li class="col-md-12 col-sm-12 col-xs-12" style="list-style:none; background-color:#ddd; margin-bottom:25px;">
              <div class="box-body gallery-item">
                  <video width="100%"controls>
                    <source src="<?php echo asset("").$promotional_video->file_path?>">
                  </video>
                  @if($promotional_video->extension != "mp4")
                    <p>Your browser does not support {{ $promotional_video->extension }} format.</p>
                  @endif
                  {{ $promotional_video->project_name }}
              </div>
            </li>
          @endforeach
        @else
          No promotional videos found
        @endif
      </div>
    </div>
  </section>
@stop