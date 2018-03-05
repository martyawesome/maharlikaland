@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Block {{ $properties[0]->block_number }}
      <small><b><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></b></small>
    </h1>
    <ol class="breadcrumb">
      <li>Projects</li>
      <li><a href="{{ URL::route('project', $project->slug) }}">{{ $project->name }}</a></li>
      <li class="active">Blocks</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    <div class="list-group">
      @foreach($properties as $property)
        @if($property->property_status_id == config('constants.PROPERTY_STATUS_FOR_SALE') or
          $property->property_status_id == config('constants.PROPERTY_STATUS_FOR_RENT'))
            <a href="{{ URL::route('property',array($project->slug,$property->slug)) }}" class="list-group-item">
          @elseif($property->property_status_id == config('constants.PROPERTY_STATUS_RESERVED'))
            <a href="{{ URL::route('property',array($project->slug,$property->slug)) }}" class="list-group-item list-group-item-warning">
          @elseif($property->property_status_id == config('constants.PROPERTY_STATUS_FORECLOSED'))
            <a href="{{ URL::route('property',array($project->slug,$property->slug)) }}" class="list-group-item list-group-item-danger">
          @elseif($property->property_status_id == config('constants.PROPERTY_STATUS_SOLD_ONGOING_DP') or
          $property->property_status_id == config('constants.PROPERTY_STATUS_SOLD_ONGOING_MA') or
          $property->property_status_id == config('constants.PROPERTY_STATUS_SOLD'))
            <a href="{{ URL::route('property',array($project->slug,$property->slug)) }}" class="list-group-item list-group-item-success">
          @else
            <a href="{{ URL::route('property',array($project->slug,$property->slug)) }}" class="list-group-item list-group-item-info">
        
          @endif
          Lot {{ $property->lot_number }} - {{ $property->property_status }}
        </a>
      @endforeach
    </div>
  </section>
@stop