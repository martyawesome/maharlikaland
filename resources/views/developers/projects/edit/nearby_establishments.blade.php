@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Nearby Establishments
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Nearby Establishments</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      <div class="box box-success">
        <div class="box-body">
          <label for"amenities">Current Nearby Establishments</label>
            @if(count($nearby_establishments)==0)
              none
            @else
              <div class="list-group">
                @for($i=0;$i < count($nearby_establishments);$i++)
                  <a href="{{ URL::route('project_edit_nearby_establishment',array($project->slug,$nearby_establishments[$i]->slug)) }}" class="list-group-item">{{$nearby_establishments[$i]->nearby_establishment}}</a>
                @endfor
              </div>
              
            @endif
        </div>
      </div>
      @include('includes.projects.create_update.nearby_establishments.add')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop