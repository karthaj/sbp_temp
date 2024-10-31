@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="question_title">
                        <h3>ShopBox Merchant Login</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.login') }}" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="step">
                            <div class="row justify-content-md-center">
                                <div class="col-md-6">              
                                    <div class="form-group row"> 
                                        <div class="col-md-12">
                                           <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Email Address" required="required">
                                           
                                           @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div> 
                                   
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password" required="required">

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                   
                                </div>
                            </div>
                        </div>

                        <div id="bottom-wizard">
                            <button type="submit" class="forward">Login</button>
                        </div>
                    </form>
                        
                    <div class="text-center">
                        <h6><a href="{{ route('admin.password.request') }}">Forgot Password</a></h6>
                        <p><a href="{{ route('activation.resend') }}">Re Send Activation link</a></p>
                        <p><a href="//myshopbox.lk/merchant/register">Don't have an account? Register for a merchant account</a></p>
                    </div>
                    
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection
