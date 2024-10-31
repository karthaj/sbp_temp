<div class="styled-select currency-selector">
	<select name="currencies">
		<option value="{{ $store->setting->currency->iso_code }}"
        @if(request()->cookie('currency') === $store->setting->currency->iso_code) selected="selected" @endif>
    	{{ $store->setting->currency->iso_code }}</option>
	@foreach($currencies as $currency)
		<option value="{{ $currency }}"
            @if(request()->cookie('currency') === $currency) selected="selected" @endif>
      	{{ $currency }}</option>
	@endforeach
	</select>
</div>