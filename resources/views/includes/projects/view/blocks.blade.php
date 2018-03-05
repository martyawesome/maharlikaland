<div>
  <h3 class="form-header">
    Blocks and Lots
  </h3>
  <div class="list-group">
    @foreach($properties as $property)
      <a href="{{ URL::route('project_block',array($project->slug,$property->block_number)) }}" class="list-group-item">Block {{ $property->block_number }}</a>
    @endforeach
  </div>
</div> 