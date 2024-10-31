<div class="tab-pane {{ request()->tab === 'profile' ? 'active' : '' }}">

  @if(session()->has('success'))
  <div class="row">
    <div class="col-sm-12 align-self-center">
      <div class="alert alert-success" role="alert">
        <i class="aapl-checkmark-circle mr-2" aria-hidden="true"></i> {{ session('success') }}
      </div>
    </div>
  </div>
  @endif

  <form action="{{ route('customer.account.update') }}" method="post">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-6 form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
        <label for="firstname">First Name</label>
        <input type="text" class="form-control{{ $errors->has('firstname') ? ' form-control-danger' : '' }}" id="firstname" name="firstname" value="{{ auth()->user()->firstname }}">
        @if($errors->has('firstname'))
          <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
        <label for="lastname">Last Name</label>
        <input type="text" class="form-control{{ $errors->has('lastname') ? ' form-control-danger' : '' }}" id="lastname" name="lastname" value="{{ auth()->user()->lastname }}">
        @if($errors->has('lastname'))
          <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group{{ $errors->has('company') ? ' has-danger' : '' }}">
        <label for="company">Company</label>
        <input type="text" class="form-control{{ $errors->has('company') ? ' form-control-danger' : '' }}" id="company" name="company" value="{{ auth()->user()->company ?: old('company')  }}">
        @if($errors->has('company'))
          <div class="form-control-feedback">{{ $errors->first('company') }}</div>
        @endif
      </div>

      <div class="col-md-6 form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
        <label for="phone">Phone Number</label>
        <input type="text" class="form-control{{ $errors->has('phone') ? ' form-control-danger' : '' }}" id="phone" name="phone" value="{{ auth()->user()->phone ?: old('phone') }}">
        @if($errors->has('phone'))
          <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
        @endif
      </div>

    </div>
    <div class="row justify-content-center mt-4">
      <div class="col-md-2">
        <button type="submit" class="btn btn-default btn-block">Update</button>
      </div>
    </div>
  </form>
</div>