@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  <section class="content-header">
    <h1>
      Joint Ventures
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Joint Ventures</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    {!! Form::model($project, array('files' => true)) !!}
      <div class="box box-success">
        <div class="box-body">
          <label for"amenities">Current Joint Ventures</label>
            @if(count($current_joint_ventures)==0)
              none
            @else
              <div class="list-group">
                @for($i=0;$i < count($current_joint_ventures);$i++)
                  <a href="{{ URL::route('project_edit_joint_venture',array($project->slug,$current_joint_ventures[$i]->slug)) }}" class="list-group-item">{{$current_joint_ventures[$i]->name}}</a>
                @endfor
              </div>
              
            @endif
        </div>
      </div>
      @include('includes.projects.create_update.joint_ventures.add')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-primary'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <script type="text/javascript" src="{{ URL::asset('js/add_project.js') }}"></script>

@stop