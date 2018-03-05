@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Amenities
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Amenities</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    {!! Form::model($project, array('files' => true)) !!}
      <div class="box box-success">
        <div class="box-body">
          <label for"amenities">Current Amenities</label>
            @if(count($current_amenities)==0)
              none
            @else
              <div class="list-group">
                @for($i=0;$i < count($current_amenities);$i++)
                  <a href="{{ URL::route('project_edit_amenity',array($project->slug,$current_amenities[$i]->slug)) }}" class="list-group-item">{{$current_amenities[$i]->amenity}}</a>
                @endfor
              </div>
              
            @endif
        </div>
      </div>
      @include('includes.projects.create_update.amenities.add')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop