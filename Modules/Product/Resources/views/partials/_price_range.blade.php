@foreach($rates as $rate)
<div id="range_{{ $loop->index }}" class="row align-items-end row-item">
    <div class="col-sm-3 form-group">
      <label for="lower_{{ $loop->index }}">From</label>
      <div class="input-group transparent">
        <input type="text" class="form-control autonumeric" id="lower_{{ $loop->index }}" name="range[{{ $loop->index }}][lower]" value="{{ ceil($rate->delimiter1) }}">
        <span class="input-group-addon">Rs</span>
      </div>
    </div>
    <div class="col-sm-3 form-group">
      <label for="upper_{{ $loop->index }}">To</label>
      <div class="input-group transparent">
        <input type="text" class="form-control autonumeric" id="upper_{{ $loop->index }}" name="range[{{ $loop->index }}][upper]" value="{{ ceil($rate->delimiter2) }}">
        <span class="input-group-addon">Rs</span>
      </div>
    </div>
    <div class="col-sm-3 form-group">
      <label for="cost_{{ $loop->index }}">Cost</label>
      <div class="input-group transparent">
        <span class="input-group-addon">Rs</span>
        <input type="text" class="form-control autonumeric" id="cost_{{ $loop->index }}" name="range[{{ $loop->index }}][cost]" value="{{ ceil($rate->price) }}">
      </div>
    </div>
    @if($loop->first)
    <div class="col-sm-3 form-group action-group">
      <button class="btn btn-outline-blue btn-remove">
            <i class="pg-trash"></i>
      </button>
    </div>
    @endif
    @if($loop->iteration == $loop->count)
    <div class="col-sm-3 form-group action-group">
        <button class="btn btn-outline-blue btn-add" type="button">
            <i class="fa fa-plus"></i>
        </button>
        <button class="btn btn-outline-blue ml-1 btn-remove">
            <i class="pg-trash"></i>
        </button>
    </div>
    @endif
</div>
@endforeach