@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item active">marketplace</li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <div class="card-title">Themes</div>
      </div>
      @if($paginated_themes->count())
      <div class="card-block">
        <div class="row">
          <div class="col-sm-3">
            <div class="card">
              <div class="card-block">
                <h6 class="mt-0"><span class="semi-bold">Price</span></h6>
                <div class="checkbox check-info">
                  <input type="checkbox" value="free" id="free" data-toggle="filter" data-filter-type="price">
                  <label for="free">Free</label>
                </div>
                <div class="checkbox check-info">
                  <input type="checkbox" value="paid" id="paid" data-toggle="filter" data-filter-type="price">
                  <label for="paid">Paid</label>
                </div>
                <hr>
                <h6 class="mt-0"><span class="semi-bold">Industry</span></h6>
                @if($industries->count())
                  @foreach($industries as $industry)
                    <div class="checkbox check-info">
                      <input type="checkbox" value="{{ $industry->slug }}" id="{{ $industry->slug }}" data-toggle="filter" data-filter-type="industry">
                      <label for="{{ $industry->slug }}">{{ $industry->name }}</label>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
          <div class="col-sm-9">
            <div class="card">
              <div class="card-block">
                <div class="row mb-4 justify-content-end">
                  <div class="col-3">
                    <select id="sort_by" class="form-control" data-toggle="filter" data-filter-type="sort">
                      <option value="newest">Newest</option>
                      <option value="featured">Featured</option>
                    </select>
                  </div>
                </div>
                <hr>
                <div id="Themes">
                  @include('zpanel.marketplace.themes._themes')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

@endsection

@section('page_scripts')
<script>
  
$(document).ready(function() {
  Shopbox.toggleFilter();
  Shopbox.filteredBy();
});

</script>
@endsection
