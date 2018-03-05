@extends('developers.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Upload Promotional Images for {{ $project->name }}
      <small><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></small>
    </h1>
    <ol class="breadcrumb">
      <li>Marketing</li>
      <li>Promotional Images</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Upload</li>
    </ol>
  </section>
  <section class="content">
     <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div style="margin-top: 30px;">
                <div class="dropzone" id="dropzoneFileUpload"></div>
                <input value="Start Upload" class="btn btn-primary" type="submit" style="margin-top:30px;" onclick="startUploading()"/>
            </div>
            <div style="padding: 30px;">
                <ul>
                    <li>Maximum allowed size of image is 15MB</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal modal-success fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Success!</h4>
          </div>
          <div class="modal-body">
            <p> Upload finished </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal1">Okay</button>
          </div>
        </div>
      </div>
    </div>

    <div id="failedModal" class="modal modal-danger fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Error!</h4>
          </div>
          <div class="modal-body" >
            <p id="modal-error-message"> Something went wrong! </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal2">Okay</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">

      $(document).ready(function(){
        $("#closeModal1").click(function(){
            window.location.href = "{{ URL::route('promotional_images') }}";
        });
        $("#closeModal2").click(function(){
          $('#failedModal').hide();
        });
      });

      var token = "{{ Session::getToken() }}";
      var errors = false;
      var errorResponse = "";
      var myDropzone = new Dropzone("div#dropzoneFileUpload", { 
        paramName: "file",
        maxFilesize: 15,
        autoProcessQueue: false,
        addRemoveLinks: true,
        parallelUploads: 1000,
        dictRemoveFile: 'Remove',
        dictFileTooBig: 'Image is bigger than 15MB',
        url: "{{ url('/') }}/manage/developers/marketing/promotional_materials/images/{{ $project->slug }}/upload",
        params: {
          _token: token
        },
        init: function() {
          this.on("error", function(file, response) {
            errors = true;
            errorResponse = response;
          });
          this.on("queuecomplete", function(file) {
            if(errors) {
              $('#failedModal').modal();
              $('#modal-error-message').text(errorResponse);
            } else {
              if(this.getUploadingFiles().length == 0 && this.getQueuedFiles().length == 0) {
                $('#myModal').modal();
              }
            }
          });
        }
      });

      function startUploading() {
        myDropzone.processQueue();
      }
     </script>
  </section>
@stop