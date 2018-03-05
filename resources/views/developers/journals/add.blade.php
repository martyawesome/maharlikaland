@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Add Journal Entry
    </h1>
    <ol class="breadcrumb">
      <li>Journals</li>
      <li class="active">Add</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($journal) !!}
      @include('includes.journals.add_edit') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
      </div>
    {!! Form::close() !!}
  </section>
@stop


