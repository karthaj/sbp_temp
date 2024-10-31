@extends('layouts.zpanel')


@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <div class="card-title">
          <h1 class="section-title">Create a new store</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-block">
                <div class="row justify-content-center">
                  <div class="col-sm-6">
                    <form action="{{ route('merchant.stores.store') }}" method="post" autocomplete="off">
                      {{ csrf_field() }}
                      <div class="form-group{{ $errors->has('store_name') ? ' has-danger' : '' }}">
                        <label for="store_name">Store Name</label>
                        <input type="text" name="store_name" id="store_name" class="form-control{{ $errors->has('form-control-danger') ? ' form-control-danger' : '' }}" value="{{ old('store_name') }}">
                        @if($errors->has('store_name'))
                          <div class="form-control-feedback">{{ $errors->first('store_name') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('domain') ? ' has-danger' : '' }}">
                        <label for="domain">Domain</label>
                         <div class="input-group">
                          <input type="text" id="domain" name="domain" class="form-control{{ $errors->has('domain') ? ' form-control-danger' : '' }}" placeholder="domain" onchange="Shopbox.domainAvailability(event)" value="{{ old('domain') }}"> 
                          <span class="input-group-addon">.myshopbox.lk</span>
                         </div>
                        @if($errors->has('domain'))
                          <div class="form-control-feedback">{{ $errors->first('domain') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('address1') ? ' has-danger' : '' }}">
                        <label for="address1">Address 1</label>
                        <input type="text" name="address1" id="address1" class="form-control{{ $errors->has('address1') ? ' form-control-danger' : '' }}" value="{{ old('address1') }}">
                        @if($errors->has('address1'))
                          <div class="form-control-feedback">{{ $errors->first('address1') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('address2') ? ' has-danger' : '' }}">
                        <label for="address2">Address 2</label>
                        <input type="text" name="address2" id="address2" class="form-control{{ $errors->has('address2') ? ' form-control-danger' : '' }}" value="{{ old('address2') }}">
                        @if($errors->has('address2'))
                          <div class="form-control-feedback">{{ $errors->first('address2') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control{{ $errors->has('city') ? ' form-control-danger' : '' }}" value="{{ old('city') }}">
                        @if($errors->has('city'))
                          <div class="form-control-feedback">{{ $errors->first('city') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('country') ? ' has-danger' : '' }}">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="form-control{{ $errors->has('country') ? ' form-control-danger' : '' }}" onchange="Shopbox.countryHasStates(event)">
                          @foreach($countries as $country)
                          <option value="{{ $country->id }}" 
                            @if(old('country') == $country->id || session('store')->country_id == $country->id)
                              selected 
                            @endif
                            >{{ $country->name }}</option>
                          @endforeach
                        </select>
                        @if($errors->has('country'))
                          <div class="form-control-feedback">{{ $errors->first('country') }}</div>
                        @endif
                      </div>

                      <div class="form-group{{ $errors->has('state') ? ' has-danger' : '' }} {{ session()->has('country') ? !session('country')->states->count() ? ' d-none' : '' : !session('store')->country->states->count() ? ' d-none' : '' }}">
                        <label for="state">State</label>
                        <select name="state" id="state" class="form-control{{ $errors->has('state') ? ' form-control-danger' : '' }}">
                          @if(session()->has('country') && session('country')->states->count())
                            <option value>Select a state</option>
                            @foreach(session('country')->states as $state)
                              <option value="{{ $state->id }}"
                                @if(old('state') == $state->id)
                                  selected 
                                @endif
                                >{{ $state->name }}</option>
                            @endforeach
                          @elseif(session('store')->country->states->count())
                            <option value>Select a state</option>
                            @foreach(session('store')->country->states as $state)
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

                      <div class="form-group{{ $errors->has('postal_code') ? ' has-danger' : '' }}">
                        <label for="postal_code">Zip/Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control{{ $errors->has('postal_code') ? ' form-control-danger' : '' }}" value="{{ old('postal_code') }}">
                        @if($errors->has('postal_code'))
                          <div class="form-control-feedback">{{ $errors->first('postal_code') }}</div>
                        @endif
                      </div>

                      <div class="form-group mt-3 text-center">
                        <button type="submit" class="btn btn-action-save">Add New Store</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')

<script>
  
  Shopbox.generateDomain();

</script>

@endsection
