@extends('developers.base_dashboard')
@section('content')
  @include('modals.success')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Account Titles
    </h1>
    <ol class="breadcrumb">
      <li>Accounting</li>
      <li class="active">Account Titles</li>
    </ol>
  </section>
  <section class="content">
    @include('modals.success')
    <div class="list-group">
      <div class="box-footer" style="margin-bottom: 15px;">
        {!! link_to_route('add_account_title', 'Add', array(), ['class' => 'btn btn-success', 'style' => 'margin-right:5px;']) !!}
      </div>
      @if(count($account_titles) > 0)
        <div class="box box-default">
          <div class="box-body">
            @foreach($account_titles as $account_title)
              <a href="{{ URL::route('edit_account_title',array($account_title->slug)) }}" class="list-group-item">
                {{ $account_title->title }}
              </a>
            @endforeach
          </div>
        </div>
      @else
        <p>No account titles found</p>
      @endif
    </div>
  </section>
@stop