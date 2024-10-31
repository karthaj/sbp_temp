@if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if($order->customer->is_guest)
<div class="list-group mt-3">
    <div class="list-group-item">
        <h6 class="font-weight-normal">Create an account for a faster checkout in the future</h6>
        <form action="{{ route('checkout.customer.create', $order) }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
                @if($errors->has('password_confirmation'))
                    <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-info" type="submit">Create Account</button>
            </div>
        </form>
    </div>
</div>
@endif