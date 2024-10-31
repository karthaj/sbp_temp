@extends('layouts.zpanel')

@section('styles')

<style>
  
  .radio.filter-section.active-filter label:before {
      border-color: #3b4752;
      border-width: 5px;
  }

</style>

@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item">marketplace</li>
  <li class="breadcrumb-item"><a href="{{ route('marketplace.plugins') }}">Plugins</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <div class="card-title">Plugins</div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-sm-3">
            <div class="card">
              <div class="card-block">
                <h6 class="mt-0"><span class="semi-bold">Price</span></h6>
                <div class="radio filter-section {{ !request()->price ? 'active-filter' : request()->price == 'all' ? 'active-filter' : '' }}">
                  <a href="/{{ request()->path() }}?price=all&sort_by={{ request()->sort_by ?: 'newest' }}">  
                    <label for="all">All</label>
                  </a>
                </div>
                <div class="radio filter-section {{ request()->price == 'free' ? 'active-filter' : '' }}">
                  <a href="/{{ request()->path() }}?price=free&sort_by={{ request()->sort_by ?: 'newest' }}">  
                    <label for="free">Free</label>
                  </a>
                </div>
                <div class="radio filter-section {{ request()->price == 'paid' ? 'active-filter' : '' }}">
                  <a href="/{{ request()->path() }}?price=paid&sort_by={{ request()->sort_by ?: 'newest' }}">
                    <label for="paid">Paid</label>
                  </a>
                </div>
                @if($categories->count())
                  <hr>
                  <h6 class="mt-0"><span class="semi-bold">Categories</span></h6>
                  @foreach($categories as $category)

                  <div class="radio filter-section {{ isset($browse_category) ? $browse_category->slug == $category->slug ?  'active-filter' : '' : '' }}">
                    <a href="/marketplace/plugins/browse/{{ $category->slug }}?price={{ request()->price ?: 'all' }}&sort_by={{ request()->sort_by ?: 'newest' }}">
                      <label for="{{ $category->slug }}">{{ $category->name }}</label>
                    </a>
                  </div>

                  @endforeach
                @endif
              </div>
            </div>
          </div>
          <div class="col-sm-9">
            <div class="card">
              <div class="card-block">
                <form id="sortFilterForm" action="/marketplace/plugins" method="post">
                  {{ csrf_field() }}
                  <div class="row mb-4 justify-content-end">
                    @if(isset($browse_category) || request()->price === 'paid' || request()->price === 'free')
                    <div class="col">
                      <strong>Filters: </strong> 

                      @if(request()->price === 'paid' || request()->price === 'free')
                        <a href="/{{ request()->path() }}?price=all&sort_by={{ request()->sort_by ?: 'newest' }}"><span class="label">{{ title_case(request()->price) }} <i class="aapl-cross"></i></span></a>
                      @endif

                      @if(isset($browse_category))
                        <a href="/marketplace/plugins?price={{ request()->price ?: 'all'}}&sort_by={{ request()->sort_by ?: 'newest' }}"><span class="label">{{ $browse_category->name }} <i class="aapl-cross"></i></span></a>
                      @endif
                      <a href="/marketplace/plugins?price=all&sort_by={{ request()->sort_by ?: 'newest' }}">Clear</a>
                    </div>
                    @endif
                    <div class="col-3">
                      <select id="sort_by" class="form-control" name="sort_by" onchange="event.preventDefault();document.getElementById('sortFilterForm').submit();">
                        <option value="/{{ request()->path() }}?price={{ request()->price ?: 'all' }}&sort_by=newest"
                          @if(request()->sort_by && request()->sort_by == 'newest')
                          selected="selected" 
                          @else
                          selected="selected"
                          @endif
                          >Newest</option>
                        <option value="/{{ request()->path() }}?price={{ request()->price ?: 'all' }}&sort_by=featured"
                          @if(request()->sort_by && request()->sort_by == 'featured')
                          selected="selected" 
                          @endif
                          >Featured</option>
                      </select>
                    </div>
                  </div>
                </form>
                <hr>
                <div class="row">
                @if($plugins->count())
                  @foreach($plugins as $plugin)
                    <div class="col-sm-4">
                      <div class="card">
                        @if($plugin->cover)
                          <img class="card-img-top img-fluid" src="{{ asset('modules/'.$plugin->alias.'/'.$plugin->cover) }}" alt="{{ $plugin->plugin_name }}">
                        @else
                          <img class="card-img-top img-fluid" src="https://via.placeholder.com/267/104?text=image" alt="{{ $plugin->plugin_name }}">
                        @endif
                        <div class="card-block">
                          <a href="{{ route('plugin.show', $plugin->slug) }}">
                            <strong>{{ $plugin->plugin_name }}</strong>
                          </a>
                          <p>
                            @if($plugin->price > 0)
                              <span>LKR {{ number_format($plugin->price, 2) }}</span>
                            @else
                              <span>free</span>
                            @endif
                          </p>
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <h6 class="mx-auto">No Plugins were found, try clearing some filters.</h6>
                @endif
                </div>
                <hr>
                {{ $plugins->appends(array_only(request()->all(), ['price', 'sort_by']))->links('vendor.pagination.bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
