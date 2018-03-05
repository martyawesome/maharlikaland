@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  @include('modals.developers.properties.delete')
  <section class="content-header">
    <h1>
      {{ $property->name }}
      @if($project)
        <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
      @endif
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">Property</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.properties.view') 
    <div class="box-footer">
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
        {!! link_to_route('edit_property', 'Edit', array($project->slug,$property->slug), ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
      @endif
      
      {!! link_to_route('property_gallery', 'Gallery', array($project->slug,$property->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}    
      
      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or $has_ledger and ((Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
      or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))))
        {!! link_to_route('ledger', 'Show Ledger', array($property->buyer_id, $property->slug), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      @endif

      @if((Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')) and !$has_ledger and
      $property->property_type_id == config('constants.PROPERTY_TYPE_LOT'))
        {!! link_to_route('property_show_split', 'Split', array($project->slug,$property->slug), ['class' => 'btn btn-warning', 'style' => 'margin-right:5px;']) !!} 
      @endif

      @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      @endif
      
    </div>
  </section>
@stop