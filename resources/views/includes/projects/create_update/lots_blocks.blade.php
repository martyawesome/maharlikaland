<div>
  <h2><small><b>Blocks and Lots</b></small></h2>
  <div class="box box-success">
    <div class="box-body">
      <div class="form-group{{ $errors->has('blocks') ? ' has-error' : '' }}">
        {!! Form::label('blocks', 'Number of Blocks*'); !!}
        {!! Form::text('blocks', null, ['class' => 'form-control']) !!}
        {!! $errors->first('blocks', '<span class="help-block">:message</span>') !!}
      </div>
      <div id="lots_container">
        @for($i = 0; $i < count(old('lots_blocks')); $i++)
          <?php $value = old('lots_blocks.'.$i)?>
          <div class="form-group{{ $errors->has('lots_blocks.$i') ? ' has-error' : '' }}" style="padding-left:30px;" id="lots_blocks_container[{{$i}}]">
            <label for="lots_blocks[{{$i}}]">Lot {{$i+1}}</label>
            <input class="form-control" placeholder="Number of blocks" name="lots_blocks[{{$i}}]" type="text" id="lots_blocks[{{$i}}]" value="{{$value}}">
            {!! $errors->first('lots_blocks.$i', '<span class="help-block">:message</span>') !!}
          </div>
        @endfor
      </div>
    </div>
  </div>  
</div>