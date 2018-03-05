@extends('agents.base_dashboard')
@section('content')
  <section class="content-header">
    <h1>
      Upload Photos for {{ $property->name }}
      <small>Properties</small>
    </h1>
    <ol class="breadcrumb">
      <li><i class="fa fa-user" style="margin-right:5px;"></i>Properties</li>
      <li class="active">All Properties</li>
    </ol>
  </section>
  <section class="content">
     @include('includes.properties.upload_photos')
  </section>
@stop