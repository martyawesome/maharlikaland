@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      Edit Journal Entry
    </h1>
    <ol class="breadcrumb">
      <li>Journals</li>
      <li class="active">Edit</li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($journal) !!}
      @include('includes.journals.add_edit') 
      <div class="box-footer">
        {!! Form::submit('Save', ['class' => 'btn btn-success'])!!}
        <input type="button" class="btn btn-danger" value="Delete" id="delete-button">
      </div>
    {!! Form::close() !!}
  </section>
  @include('modals.delete')
  <script type="text/javascript">
    var journal_id = "<?php echo $journal->id; ?>";
  </script>
  <script type="text/javascript" src="{{ URL::asset('js/delete_journal.js') }}"></script>
@stop


