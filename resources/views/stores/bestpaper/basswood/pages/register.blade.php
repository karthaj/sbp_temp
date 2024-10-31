@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('register') }}">Register</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')

<div class="login-area pt-50 pb-20">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12 col-lg-6 col-xl-6 ml-auto mr-auto">
                <div class="login">
                    <div class="login-form-container">
                        <div class="row col-12 text-center">
                            <p>Already have an account? 
                                <a href="{{ route('login') }}">Log in instead!</a>
                            </p>
                        </div>
                        <div class="login-form">
                            <form class="row" action="{{ route('register') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group col-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}">
                                    @if($errors->has('first_name'))
                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group col-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}">
                                    @if($errors->has('last_name'))
                                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group col-6">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="form-group col-6">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div> 
                                
                                <div class="form-group col-6">
                                    <label for="password">Confirm password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                                    @if($errors->has('password_confirmation'))
                                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                    @endif
                                </div> 
                               
                                <div class=" col-12 button-box">
                                    <button type="submit" class="btn-block default-btn">REGISTER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection