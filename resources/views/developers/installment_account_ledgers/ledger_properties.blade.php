@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      {{ $buyer->first_name }} {{ $buyer->middle_name }}  {{ $buyer->last_name }}'s Properties
    </h1>
    <ol class="breadcrumb">
      <li>Installment Account Ledger</li>
      <li class="active">Buyers</li>
      <li class="active">Properties</li>
    </ol>
  </section>
  <section class="content">
    <div class="list-group">
      @if(count($ledger_properties) > 0)
        @foreach($ledger_properties as $ledger_property)
          <a href="{{ URL::route('ledger',array($buyer->id,$ledger_property->slug)) }}" class="list-group-item">
            {{ $ledger_property->name }}
          </a>
        @endforeach
      @else
        No properties found for the buyer
      @endif
    </div>
  </section>
@stop