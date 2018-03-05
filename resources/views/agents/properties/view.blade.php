@extends('agents.base_dashboard')
@section('content')
  @include('modals.delete_agent_property')
  <section class="content-header">
    <h1>
      Edit Property
      <small>Properties</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">Edit Property</li>
    </ol>
  </section>
  <section class="content">
    @include('includes.properties.view') 
    <div class="box-footer">
      {!! link_to_route('agent_edit_property', 'Edit', [$property->slug], ['class' => 'btn btn-primary', 'style' => 'margin-right:5px;']) !!}
      {!! link_to_route('property_gallery', 'Gallery', [$property->slug], ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      <div id="deletePropertyButton" class="btn btn-danger">Delete</div>
    </div>
  </section>
@stop