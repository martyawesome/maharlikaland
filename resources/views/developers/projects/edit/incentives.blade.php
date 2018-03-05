@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Incentives
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Incentives</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($project, array('files' => true)) !!}
      <div class="box box-success">
        <div class="box-body">
          <label for"amenities">Current Incentives</label>
            @if(count($current_incentives)==0)
              none
            @else
              <div class="list-group">
                @for($i=0;$i < count($current_incentives);$i++)
                  <a href="{{ URL::route('project_edit_incentive',array($project->slug,$current_incentives[$i]->slug)) }}" class="list-group-item">{{$current_incentives[$i]->incentive}}</a>
                @endfor
              </div>
              
            @endif
        </div>
      </div>
      @include('includes.projects.create_update.incentives.add')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop