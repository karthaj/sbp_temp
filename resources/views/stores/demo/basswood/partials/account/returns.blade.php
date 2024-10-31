<div class="tab-pane {{ request()->tab === 'return_list' || request()->tab === 'return' ? 'active' : '' }}">

@if(request()->tab === 'return')
	@if($errors->has('return_qty.*'))
		<div class="alert alert-info" role="alert">
		    <i class="fa fa-exclamation-circle mr-2" aria-hidden="true"></i> Please select one or more items to return.
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
	@endif

	@if(session()->has('info'))
		<div class="alert alert-info" role="alert">
		    <i class="fa fa-exclamation-circle mr-2" aria-hidden="true"></i> {{ session('info') }}
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
	@endif
	<div class="table-responsive">
	   	<form action="{{ route('customer.order.return', $order) }}" method="post">
	   		{{ csrf_field() }}
		    <table class="table table-bordered">
		        <thead class="thead-default">
		          <tr>
		            <th width="60%">Product</th>
		            <th scope="col">Price</th>
		            <th scope="col">Return Qty</th>
		          </tr>
		        </thead>
		        <tbody>
		        @foreach($order->details as $detail)
		          <tr>
		            <td>{{ $detail->product_name }}</td>
		            <td>{{ $detail->order->currency->iso_code }}  {{ number_format($detail->product_price,2) }}</td>
		            <td>
		            	<select name="return_qty[{{ $detail->id }}]" class="form-control">
		            		@if($detail->return_detail)
		            			@for ($i = 0; $i <= ($detail->product_quantity - $detail->return_detail->quantity); $i++)
								    <option value="{{ $i }}">{{ $i }}</option>
								@endfor
		            		@else
								@for ($i = 0; $i <= $detail->product_quantity; $i++)
								    <option value="{{ $i }}">{{ $i }}</option>
								@endfor
		            		@endif
		            	</select>
		            </td>
		          </tr>
		        @endforeach
		        </tbody>
		    </table>
		    <div class="form-group">
		    	<label for="return_reason">Return Reason</label>
		    	<textarea class="form-control{{ $errors->has('return_reason') ? ' is-invalid' : '' }}" name="return_reason" id="return_reason" cols="30" rows="3"></textarea>
		    	@if($errors->has('return_reason'))
		          <div class="invalid-feedback">{{ $errors->first('return_reason') }}</div>
		        @endif
		    </div>
		   
	      	<div class="form-group col-sm-3 mt-5 mx-auto">
		    	<button type="submit" class="btn btn-default btn-block">Submit</button>
		    </div>
		   
	    </form>
    </div>
@elseif(request()->tab === 'return_list')
	@if(session()->has('success'))
	  <div class="row">
	    <div class="col-sm-12 align-self-center">
	      <div class="alert alert-success" role="alert">
	        <i class="fa fa-check-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
	      </div>
	    </div>
	  </div>
  	@endif
  	@if(auth()->user()->returns->count())
  		@foreach(auth()->user()->returns as $return)
			<h5>Return #{{ $return->id }}</h5>
		  	<div class="table-responsive">
		  		<table class="table table-bordered">
				    <thead class="thead-default">
				      <tr>
				        <th width="50%">Product</th>
				        <th scope="col">Returned</th>
				      </tr>
				    </thead>
				    <tbody>
				    @foreach($return->details as $detail)
				    <tr>
				   		<td>{{ $detail->orderDetail->product_name }}</td>
				   		<td>{{ $detail->quantity }}</td>
				   	</tr>
				    @endforeach
				    </tbody>
				</table>
				<p><span class="font-weight-bold">Date:</span> {{ $return->created_at->toFormattedDateString() }}</p>
				<p><span class="font-weight-bold">Status:</span> {{ $return->status->name }}</p>
				@if(!$loop->last)
				<hr class="my-4">
				@endif
		  	</div>
  		@endforeach
  	@else
  		<div class="alert alert-info" role="alert">
	        You haven't placed any returns.
	    </div>
  	@endif
@endif
</div>