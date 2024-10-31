<div class="tab-pane {{ request()->tab === 'profile' ? 'active' : '' }}">

  @if(session()->has('success'))
  <div class="row">
    <div class="col-sm-12 align-self-center">
      <div class="alert alert-primary" role="alert">
        <i class="ti-check mr-2" aria-hidden="true"></i> {{ session('success') }}
      </div>
    </div>
  </div>
  @endif

  <form action="{{ route('customer.account.update') }}" method="post">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-6 form-group">
        <label for="firstname">First Name</label>
        <input type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" id="firstname" name="firstname" value="{{ auth()->user()->firstname }}">
        @if($errors->has('firstname'))
          <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group">
        <label for="lastname">Last Name</label>
        <input type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" id="lastname" name="lastname" value="{{ auth()->user()->lastname }}">
        @if($errors->has('lastname'))
          <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group">
        <label for="company">Company</label>
        <input type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" id="company" name="company" value="{{ auth()->user()->company ?: old('company')  }}">
        @if($errors->has('company'))
          <div class="invalid-feedback">{{ $errors->first('company') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group">
        <label for="phone">Phone Number</label>
        <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ auth()->user()->phone ?: old('phone') }}">
        @if($errors->has('phone'))
          <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
        @endif
      </div>

    </div>
    <div class="row justify-content-center mt-4">
      <div class="col-md-2">
        <button type="submit" class="btn_1 btn-block">Update</button>
      </div>
    </div>
  </form>
</div>