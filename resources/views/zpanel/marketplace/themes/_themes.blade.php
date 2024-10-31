<div class="row">
  @if(count($themes))
    @foreach($themes as $theme)
    <div class="col-sm-4">
      <div class="card">
        <div class="scrollable">
          <div style="height: 200px;">
            @if($theme['desktop_screenshot'])
              <img class="card-img-top img-fluid" src="{{ $theme['desktop_screenshot'] }}" alt="{{ $theme['name'] }}">
            @else
              <img class="card-img-top img-fluid" src="https://via.placeholder.com/280/340?text=image" alt="{{ $theme['name'] }}">
            @endif
          </div>
        </div>
        <div class="card-block">
          <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('theme.show', $theme['theme_id']) }}">
              <strong>{{ $theme['name'] }}</strong>
            </a>
            <span>{{ $theme['price'] }}</span>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  @else
    <h6 class="mx-auto">No Themes were found, based on your filters</h6>
  @endif
</div>
<hr>

{{ $paginated_themes->appends(array_only(request()->all(), ['industry', 'price', 'sort_by']))->links('vendor.pagination.bootstrap-4') }}