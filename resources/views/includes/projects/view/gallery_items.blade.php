<div class="box box-success">
  <div class="box-body">
    <ul class="row list-group">
      @if(count($gallery)==0)
        <div class="box-body">
          No photos found
        </div>
      @else
        @foreach($gallery as $project_gallery)
          <li class="col-md-6 col-sm-12" style="list-style:none;">
            <div class="box-body gallery-item">
                <img id="<?php echo $project_gallery->id?>" class="img-responsive" src="<?php echo asset("").$project_gallery->image_path?>" alt="Gallery picture">                    
                @if($project_gallery->image_path == $project->main_picture_path)
                  <p id="main-photo-identifier">Main photo</p>
                @endif
            </div>
          </li>
        @endforeach
      @endif
    </ul>
  </div> 
</div>