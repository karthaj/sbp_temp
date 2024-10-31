@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('login') }}">Login</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')

<div class="login-area pt-90">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12 col-lg-6 col-xl-6 ml-auto mr-auto">
            	@if(session()->has('success'))
				    <div class="alert alert-success">
				      {{ session('success') }}
				    </div>
				@endif
				@if(session()->has('error'))
				    <div class="alert alert-danger">
				      {{ session('error') }}
				    </div>
				@endif
                <div class="login">
                    <div class="login-form-container">
                        <div class="login-form">
                            <form method="post" action="{{ route('login') }}">
                            	{{ csrf_field() }}
                            	<div class="form-group">
                            		<label for="email">Email</label>
                                	<input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                                	@if($errors->has('email'))
							          	<div class="invalid-feedback">{{ $errors->first('email') }}</div>
							        @endif
                            	</div>

                            	<div class="form-group">
						          <label for="password">Password</label>
						          <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
						          @if($errors->has('password'))
						          	<div class="invalid-feedback">{{ $errors->first('password') }}</div>
						          @endif
						        </div>
                                
                                <div class="button-box">
                                    <div class="login-toggle-btn">
                                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                                    </div>
                                    <button type="submit" class="default-btn">Login</button>
                                </div>
                            </form>
                            <div class="no-account">
                                <a href="{{ route('register') }}">No account? Create one here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection