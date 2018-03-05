@extends('developers.base_dashboard')
@section('content')
  @include('modals.danger')
  <section class="content-header">
    <h1>
      {{ $property->name }}
      <small>{{ $project->name }}</small>
    </h1>
    <ol class="breadcrumb">
      <li></i>Projects</li>
      <li class="active"><a href="{{ URL::route('property', $property->slug) }}">{{ $property->name }}</a></li>
    </ol>
  </section>
  <section class="content">
    {!! Form::model($property,['id' => 'myForm']) !!}
      <div class="box box-success">
        <div class="box-body" style="padding-top:0px">
          <div class="control-group">
            <label class="control-label">Total Lot Area</label>
            <div class="controls readonly">{{ $property->lot_area }}</div>
          </div>
        </div>
      </div>  
      <div class="box box-default">
        <div class="box-body">
          <div class="form-group{{ $errors->has('lots') ? ' has-error' : '' }}">
            {!! Form::label('lots', 'Number of Lots'); !!}
            {!! Form::text('lots', '', ['class' => 'form-control']) !!}
            {!! $errors->first('lots', '<span class="help-block">:message</span>') !!}
          </div>
          <div id="lots_container">
            <?php $current_alphabet = 'A'; ?>
            @for($i = 0; $i < (old('lots')); $i++)
              <?php 
                $value = old('lots_blocks.'.$i);
                if($i > 0) $current_alphabet++;
              ?>
              <div class="form-group{{ $errors->has('lots_blocks.'.$i) ? ' has-error' : '' }}" style="padding-left:30px;" id="lots_blocks_container[{{$i}}]">
                <label for="lots_blocks[{{$i}}]">Lot {{$property_location->lot_number}}-{{$current_alphabet}}</label>
                <input class="form-control" placeholder="Number of blocks" name="lots_blocks[{{$i}}]" type="text" id="lots_blocks[{{$i}}]" value="{{$value}}">
                {!! $errors->first('lots_blocks.'.$i, '<span class="help-block">Lot Area is required</span>') !!}
              </div>
            @endfor
          </div>
        </div>
      </div>  
      <div class="box-footer">
        {!! Form::submit('Split', ['class' => 'btn btn-primary', 'id' => 'submit-form-button'])!!}
      </div>
    {!! Form::close() !!}
  </section>
  <div id="dangerModal" class="modal modal-danger fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Error!</h4>
        </div>
        <div class="modal-body">
          <p id="modal-input"> Invalid lot area</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="closeDangerModal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div id="invalid-security-code-modal" class="modal modal-danger fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Invalid security code</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="security-code-modal" class="modal modal-warning fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Security</h4>
      </div>
        <div class="modal-body">
          <div class="form-group">
            {!! Form::label('security_code', 'Security Code*'); !!}
            {!! Form::password('security_code', ['class'=>'form-control', 'id' => 'security_code']) !!}
          </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="close-security-code-button">Close</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" id="submit-security-code-button">Submit</button>
        </div>
    </div>
  </div>
</div>
  <script type="text/javascript">
    var lot_area = "<?php echo $property->lot_area; ?>";
    var base_lot_number = "<?php echo $property_location->lot_number?>";
    var base_url = "<?php echo url('/'); ?>";
    var project_slug = "<?php echo $project->slug; ?>";
    var property_slug = "<?php echo $property->slug; ?>";

  </script>
  <script type="text/javascript" src="{{ URL::asset('js/split_property.js') }}"></script>

@stop