@extends($theme_path.'.layouts.theme')

@section('styles')

<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/account.css') }}">

@endsection

@section('content')

	<div id="my-account" class="container-fluid">
    <div class="row my-2">
        
        <div class="col-md-3">
            <form action="{{ route('customer.avatar.upload') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row d-flex justify-content-center">
                    <div class="profile-card mb-1">
                        <div class="card-img">
                            <img id="profileImg" class="card-img-top rounded-circle img-fluid" src="{{ auth()->user()->avatar ? asset('profiles/'.auth()->user()->id.'/'.auth()->user()->avatar) : '//placehold.it/100' }}" alt="avatar">
                        </div>
                        <div class="profile-upload">
                            <label class="custom-file">
                                <input type="file" id="avatar" name="avatar" class="custom-file-input" hidden="hidden">
                                <span class="btn btn-xs custom-file-control"><i class="fa fa-camera"></i></span>
                            </label>
                        </div>
                    </div>   
                </div>
            </form>
            
            <div class="row">
                <div class="col-12 profile-info text-center">
                   <h5>{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</h5> 
                </div>
                <div class="col-12 profile-info text-center">
                   <h6>{{ auth()->user()->email }}</h6> 
                </div>
                
                <div class="col-12 profile-info text-center">
                   <h6><em>Since {{ auth()->user()->created_at->format('F Y') }}</em></h6>
                </div>
            </div>
            @if($errors->has('avatar'))
            <div class="row">
                <div class="col-sm-12">
                  <div class="alert alert-info" role="alert">
                    <i class="fa fa-exclamation-circle mr-2" aria-hidden="true"></i>  {{ $errors->first('avatar') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                </div>
            </div>
            @endif
            <hr class="profile-hr">
            <div class="row profile-stats">
                <div class="col-6 stats-card text-center">
                   <h4>{{ auth()->user()->orders()->where('store_id', session('store')->id)->count() }}</h4> 
                   <h6>TOTAL ORDERS</h6> 
                </div>
                <div class="col-6 stats-card text-center">
                   <h4>{{ $awaiting_delivery }}</h4>
                   <h6>AWAITING DELIVERY</h6>
                </div>
            </div>
            <hr class="profile-hr">
        </div>
        
        <div class="col-md-9">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="/account?tab=profile" class="nav-link {{ request()->tab === 'profile' ? 'active' : '' }}">Profile</a>
                </li>
                <li class="nav-item">
                    <a href="/account?tab=address_list" class="nav-link {{ request()->tab === 'address_list' || request()->tab === 'add_address' ? 'active' : '' }}">Addresses</a>
                </li>
                <li class="nav-item">
                    <a href="/account?tab=order_list" class="nav-link {{ request()->tab === 'order_list' || request()->tab === 'view_order' ? 'active' : '' }}">Orders</a>
                </li>
                @if(session('store')->setting->enable_returns)
                <li class="nav-item">
                    <a href="/account?tab=return_list" class="nav-link {{ request()->tab === 'return_list' || request()->tab === 'return' ? 'active' : '' }}">Returns</a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="/account?tab=wishlist" class="nav-link {{ request()->tab === 'wishlist' ? 'active' : '' }}">Wishlist</a>
                </li>
                <li class="nav-item">
                    <a href="/account?tab=store_list" class="nav-link {{ request()->tab === 'store_list' ? 'active' : '' }}">Stores</a>
                </li>
            </ul>
            <div class="tab-content py-4">

                @include($theme_path.'.partials.account.edit-account')

                @include($theme_path.'.partials.account.addresses')
            
                @include($theme_path.'.partials.account.orders')
            @if(session('store')->setting->enable_returns)
                @include($theme_path.'.partials.account.returns')
            @endif
                @include($theme_path.'.partials.account.stores')
                
                @include($theme_path.'.partials.account.wishlist')

            </div>
        </div>
        
    </div>
</div>

@endsection