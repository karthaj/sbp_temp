<div class="tab-pane {{ request()->tab === 'address_list' || request()->tab === 'add_address' ? 'active' : '' }}">

@if(request()->tab === 'address_list')

  <div class="row">
    @if(auth()->user()->addresses->count())
      @foreach(auth()->user()->addresses as $address)
       <div class="col-sm-12 col-md-6  col-lg-4 card-deck">
         <div class="card">
          <div class="card-header">{{ $address->alias }}</div>
           <div class="card-block">
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
           <div class="card-block">
              <div class="card-text text-center">
                <i class="aapl-plus-circle h3 align-middle" aria-hidden="true"></i>
                <h5>New Address</h5>
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
    
    <div class="col-md-12 form-group{{ $errors->has('alias') ? ' has-danger' : '' }}">
      <label for="alias">Location</label>
      <input type="text" class="form-control{{ $errors->has('alias') ? ' form-control-danger' : '' }}" name="alias" id="alias" value="{{ old('alias') }}">
      @if($errors->has('alias'))
        <div class="form-control-feedback">{{ $errors->first('alias') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
      <label for="firstname">First Name</label>
      <input type="text" class="form-control{{ $errors->has('firstname') ? ' form-control-danger' : '' }}" id="firstname" name="firstname" value="{{ old('firstname') }}">
      @if($errors->has('firstname'))
        <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
      <label for="lastname">Last Name</label>
      <input type="text" class="form-control{{ $errors->has('lastname') ? ' form-control-danger' : '' }}" id="lastname" name="lastname" value="{{ old('lastname') }}">
      @if($errors->has('lastname'))
        <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('company') ? ' has-danger' : '' }}">
      <label for="company">Company</label>
      <input type="text" class="form-control{{ $errors->has('company') ? ' form-control-danger' : '' }}" id="company" name="company" value="{{ old('company') }}">
      @if($errors->has('company'))
        <div class="form-control-feedback">{{ $errors->first('company') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
      <label for="phone">Phone Number</label>
      <input type="text" class="form-control{{ $errors->has('phone') ? ' form-control-danger' : '' }}" id="phone" name="phone" value="{{ old('phone') }}">
      @if($errors->has('phone'))
        <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('address1') ? ' has-danger' : '' }}">
      <label for="address1">Address 1</label>
      <input type="text" class="form-control{{ $errors->has('address1') ? ' form-control-danger' : '' }}" id="address1" name="address1" value="{{ old('address1') }}">
      @if($errors->has('address1'))
        <div class="form-control-feedback">{{ $errors->first('address1') }}</div>
      @endif
    </div>
    <div class="col-md-6 form-group{{ $errors->has('address2') ? ' has-danger' : '' }}">
      <label for="address2">Address 2</label>
      <input type="text" class="form-control{{ $errors->has('address2') ? ' form-control-danger' : '' }}" id="address2" name="address2" value="{{ old('address2') }}">
      @if($errors->has('address2'))
        <div class="form-control-feedback">{{ $errors->first('address2') }}</div>
      @endif
    </div>
    
    <div class="col-md-6 form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
      <label for="city">City</label>
      <input type="text" class="form-control{{ $errors->has('city') ? ' form-control-danger' : '' }}" id="city" name="city" value="{{ old('city') }}">
      @if($errors->has('city'))
        <div class="form-control-feedback">{{ $errors->first('city') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('country') ? ' has-danger' : '' }}">
      <label for="country">Country</label>
      <select class="form-control{{ $errors->has('country') ? ' form-control-danger' : '' }}" id="account_address_country" name="country">
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
        <div class="form-control-feedback">{{ $errors->first('city') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('state') ? ' has-danger' : '' }} {{ session()->has('country') ? !session('country')->states->count() ? 'd-none' : '' : 'd-none' }}">
      <label for="state">State</label>
      <select class="form-control{{ $errors->has('state') ? ' form-control-danger' : '' }}" id="account_address_state" name="state">
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
        <div class="form-control-feedback">{{ $errors->first('state') }}</div>
      @endif
    </div>

    <div class="col-md-6 form-group{{ $errors->has('zipcode') ? ' has-danger' : '' }}">
      <label for="zipcode">Zipcode/Postcode</label>
      <input type="text" class="form-control{{ $errors->has('zipcode') ? ' form-control-danger' : '' }}" id="zipcode" name="zipcode" value="{{ old('zipcode') }}">
      @if($errors->has('zipcode'))
        <div class="form-control-feedback">{{ $errors->first('zipcode') }}</div>
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