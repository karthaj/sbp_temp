<div class="tab-pane {{ request()->tab === 'address_list' || request()->tab === 'add_address' ? 'active' : '' }}">

@if(request()->tab === 'address_list')

  <div class="row">
    @if(auth()->user()->addresses->count())
      @foreach(auth()->user()->addresses as $address)
       <div class="col-sm-12 col-md-6  col-lg-4 card-deck">
         <div class="card">
          <div class="card-header">{{ $address->alias }}</div>
           <div class="card-body">
            <h5 class="card-title">{{ $address->firstname }} {{ $address->lastname }}</h5>
            <address>
              @if($address->company)
              {{ $address->company }} <br>
              @endif
              
              {{ $address->address }} <br>

              @if($address->address2)
              {{ $address->address2 }} <br>
              @endif

              {{ $address->city }},

              @if($address->state)
              {{ $address->state->name }} {{ $address->zip_code }}
              @else
              {{ $address->zip_code }}
              @endif
            </address>
            <p>Phone: {{ $address->phone }}</p>
            <form action="{{ route('customer.address.delete', $address->id) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" class="btn btn-default">Delete</button>
            </form>
           </div>
         </div>
       </div>
      @endforeach
    @endif
    
    <div class="col-sm-12 col-md-6 col-lg-4">
      <a href="/account?tab=add_address">
        <div class="card-deck">
          <div class="card">
           <div class="card-body">
              <div class="card-text text-center">
                <i class="fa fa-plus-circle fa-3x align-middle" aria-hidden="true"></i>
                <h5 class="mt-4">New Address</h5>
              </div>
           </div>
          </div>    
       </div>
      </a>
    </div>
  </div>

@elseif(request()->tab === 'add_address')

<form action="{{ route('customer.address.store') }}" method="post">
  {{ csrf_field() }}
  <div class="row">
    
    <div class="col-md-12 form-group">
      <label for="alias">Location</label>
      <input type="text" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" name="alias" id="alias" value="{{ old('alias') }}">
      @if($errors->has('alias'))
        <div class="invalid-feedback">{{ $errors->first('alias') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="firstname">First Name</label>
      <input type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" id="firstname" name="firstname" value="{{ old('firstname') }}">
      @if($errors->has('firstname'))
        <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="lastname">Last Name</label>
      <input type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" id="lastname" name="lastname" value="{{ old('lastname') }}">
      @if($errors->has('lastname'))
        <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="company">Company</label>
      <input type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" id="company" name="company" value="{{ old('company') }}">
      @if($errors->has('company'))
        <div class="invalid-feedback">{{ $errors->first('company') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="phone">Phone Number</label>
      <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}">
      @if($errors->has('phone'))
        <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="address1">Address 1</label>
      <input type="text" class="form-control{{ $errors->has('address1') ? ' is-invalid' : '' }}" id="address1" name="address1" value="{{ old('address1') }}">
      @if($errors->has('address1'))
        <div class="invalid-feedback">{{ $errors->first('address1') }}</div>
      @endif
    </div>
    <div class="col-md-6 form-group">
      <label for="address2">Address 2</label>
      <input type="text" class="form-control{{ $errors->has('address2') ? ' is-invalid' : '' }}" id="address2" name="address2" value="{{ old('address2') }}">
      @if($errors->has('address2'))
        <div class="invalid-feedback">{{ $errors->first('address2') }}</div>
      @endif
    </div>
    
    <div class="col-md-6 form-group">
      <label for="city">City</label>
      <input type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" id="city" name="city" value="{{ old('city') }}">
      @if($errors->has('city'))
        <div class="invalid-feedback">{{ $errors->first('city') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="country">Country</label>
      <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" id="account_address_country" name="country">
        <option value>Select a country</option>
        @foreach($countries as $country)
        <option value="{{ $country->id }}" 
          @if(old('country') == $country->id)
            selected 
          @endif
          >{{ $country->name }}</option>
        @endforeach
      </select>
      @if($errors->has('country'))
        <div class="invalid-feedback">{{ $errors->first('city') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group {{ session()->has('country') ? !session('country')->states->count() ? 'd-none' : '' : 'd-none' }}">
      <label for="state">State</label>
      <select class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" id="account_address_state" name="state">
        @if(session()->has('country') && session('country')->states->count())
          <option value>Select a state</option>
        @foreach(session('country')->states as $state)
          <option value="{{ $state->id }}"
            @if(old('state') == $state->id)
              selected 
            @endif
            >{{ $state->name }}</option>
        @endforeach
        @endif
      </select>
      @if($errors->has('state'))
        <div class="invalid-feedback">{{ $errors->first('state') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group">
      <label for="zipcode">Zipcode/Postcode</label>
      <input type="text" class="form-control{{ $errors->has('zipcode') ? ' is-invalid' : '' }}" id="zipcode" name="zipcode" value="{{ old('zipcode') }}">
      @if($errors->has('zipcode'))
        <div class="invalid-feedback">{{ $errors->first('zipcode') }}</div>
      @endif
    </div>

    <div class="col-md-12">
      <div class="form-group custom-control custom-checkbox{{ $errors->has('address_default') ? ' has-danger' : '' }}">
        <input type="checkbox" class="custom-control-input form-control{{ $errors->has('address_default') ? ' form-control-danger' : '' }}" id="address_default" name="address_default" value="1">
        <label class="custom-control-label" for="address_default">Set as default address</label>
      </div>
      @if($errors->has('address_default'))
        <div class="form-control-feedback">{{ $errors->first('address_default') }}</div>
      @endif
    </div>
    
  </div>
  <div class="row justify-content-center mt-4">
    <div class="col-md-2">
      <button type="submit" class="btn btn-default btn-block">Save</button>
    </div>
  </div>
</form>

@endif
    
</div>