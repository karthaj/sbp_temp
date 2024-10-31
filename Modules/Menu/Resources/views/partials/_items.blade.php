@foreach($items as $item)

<li class="dd-item dd3-item" data-id="{{ $item->id }}" >
  <div class="dd-handle dd3-handle">
    Drag
  </div>
  <div class="dd3-content" data-toggle="collapse" data-parent="#menuItems" href="#menu-item-{{ $item->id }}" aria-expanded="true" aria-controls="customLinks">
    {{ $item->name }}
    <small class="pull-right"><em>{{ title_case($item->type) }}</em></small>
  </div>

  	<div id="menu-item-{{ $item->id }}" class="menu-content card-block collapse{{ $errors->has('menu_item_url.'.$loop->iteration) ? ' show' : '' }}" role="tabcard" aria-labelledby="{{ $item->id }}">
  		
  		<div class="row">
  			<div class="col-sm-6">
  				<div class="form-group{{ $errors->has('menu_item_url.'.$loop->iteration) ? ' has-danger' : '' }}">
					<label for="url">URL</label>
					<input type="text" name="menu_item_url[{{ $item->id }}]" value="{{ $item->url }}" class="form-control{{ $errors->has('menu_item_url.'.$loop->iteration) ? ' form-control-danger' : '' }}" required>
					@if($errors->has('menu_item_url.'.$loop->iteration))
		            <div class="form-control-feedback">{{ $errors->first('menu_item_url.'.$loop->iteration) }}</div>
		            @endif
				</div>
  			</div>
  			<div class="col-sm-6">
  				<div class="form-group{{ $errors->has('menu_item_label.'.$loop->iteration) ? ' has-danger' : '' }}">
					<label for="menu_label">Menu Label</label>
					<input type="text" name="menu_item_label[{{ $item->id }}]" value="{{ $item->name }}" class="form-control{{ $errors->has('menu_item_label.'.$loop->iteration) ? ' form-control-danger' : '' }}" required>
					@if($errors->has('menu_item_label.'.$loop->iteration))
		            <div class="form-control-feedback">{{ $errors->first('menu_item_label.'.$loop->iteration) }}</div>
		            @endif
				</div>
  			</div>
  		</div>
	
		<div class="form-group">
			<button data-id="{{ $item->id }}" class="btn btn-default btn-default-custom item-trash" type="button"><i class="pg-trash"></i>
	        </button>
		</div>
	</div>
	
	@if($item->children->count())

	<ol class="dd-list">

	@include ('menu::partials._items', ['items' => $item->children])

	</ol>

	@endif

</li>

@endforeach