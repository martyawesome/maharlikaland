@extends('developers.base_dashboard')
@section('content')
@include('modals.danger')
  <section class="content-header">
    <h1>
      Add Journal Type
    </h1>
    <ol class="breadcrumb">
      <li>Journal Types</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($journal_type) !!}
      @include('includes.journals.journal_types.add_edit')
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop