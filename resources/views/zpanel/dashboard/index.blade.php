@extends('layouts.zpanel')

@section('content')
<ol class="breadcrumb">
    <li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<div id="app">

<div class="container-fluid container-fixed-lg">
    <div class="row align-items-end mb-3">
        <div class="col-md-7 col-12 mb-3">
            <h2 class="font-weight-bold">Welcome {{ auth()->user()->first_name.' '.auth()->user()->last_name }},</h2>
            <p>Store: {{ session('store')->store_name }}</p>
            <p>Plan: {{ session('store')->plan->name }} <span class="text-hint">({{ session('store')->expiry_date->format("l". ", " . "d M Y" ) }})</span> 
            @if(auth()->user()->master && $current_date->lessThan(session('store')->expiry_date))
                <a class="btn btn-xs btn-danger ml-2" href="{{ route('plan.change.index') }}"> Change Plan</a>
            @endif
            </p>
        </div>
        @if(auth()->user()->master)
        <div class="col-md-5 col-12">
            <div class="row justify-content-end">
                <div class="col-6">
                    <div class="card">
                        <div class="card-block">
                            <a href="{{ route('merchant.stores.create') }}" class="btn btn-action-add btn-block">New Business</a>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->stores->count() > 1)
                <div class="col-6">
                     <div class="card">
                        <div class="card-block">
                            <a href="{{ url('/merchant/manage/store') }}" class="btn btn-action-add btn-block">Change store</a>
                        </div>
                    </div> 
                </div>
                 @endif    
            </div>      
        </div>
        @endif
    </div>

    <div class="card card-transparent">
        <div class="card-title">Here’s what’s happening with your store today.</div>
        <div class="card-block">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card text-center">
                        <div class="card-block">
                            <h3>{{ number_format($sales, 2) }}</h3>
                            <p class="text-hint">Sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h3 class="font-weight-bold">{{ $orders }}</h3>
                            <p class="text-hint">Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h3 class="font-weight-bold">{{ $visits }}</h3>
                            <p class="text-hint">Visits</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <a href="{{ route('payouts.index') }}">
                        <div class="card text-center">
                            <div class="card-block">
                                <h3>LKR {{ number_format($payouts, 2) }}</h3>
                                <p class="text-hint">Payout Balance</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </fiv>
    
    
    <div class="card mb-5">
        <div class="card-header">
            <div class="card-title">Incomplete orders</div>
        </div>
        <div class="card-block">
            <div class="row justify-content-center">
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h1>{{ $new_orders }}</h1>
                            <p class="text-hint text-uppercase">New <br>orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h1 class="font-weight-bold">{{ $pending_orders }}</h1>
                            <p class="text-hint text-uppercase">Pending <br>orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h1 class="font-weight-bold">{{ $awaiting_shipment }}</h1>
                            <p class="text-hint text-uppercase">To be <br>shipped</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h1 class="font-weight-bold">{{ $shipped }}</h1>
                            <p class="text-hint text-uppercase">Already <br>Shipped</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="card text-center">
                        <div class="card-block">
                            <h1 class="font-weight-bold">{{ $pending_payments }}</h1>
                            <p class="text-hint text-uppercase">Pending <br>payments</p>
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


@endsection
