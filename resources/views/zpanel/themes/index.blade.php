@extends('layouts.zpanel')

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item active">themes</li>
</ol>
<!-- END BREADCRUMB --> 
  <div  class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">Themes</h1>
    </div>
    <div class="card-block">
        <h4>
          <span class="semi-bold">Active Theme</span>
        </h4>
        <div class="sm-no-padding">
         <div class="card card-transparent">
            <div class="card-block no-padding">
              <div class="card card-default">
                <div class="card-block">
                  <div class="row">
                    <div class="col-md-4">
                      <div style="height: 230px; background: url({{ $active_theme['screenshot'] }})" class="img-thumbnail theme-preview"></div>
                    </div>
                    <div class="col-md-8">
                      <h4>
                        <span class="semi-bold">{{ $active_theme['name'] }}</span>
                      </h4>
                      <h6><span class="semi-bold">Version:</span> {{ $active_theme['current_version'] }} &emsp; <span class="semi-bold">Author:</span> {{ $active_theme['author'] }}</h6>
                      <p class="card-text">
                        {{ $active_theme['description'] }}
                      </p>
                      @if(session('store')->domain === 'demo')
                        <a href="{{ $active_theme['customize_uri'] }}" class="btn btn-complete btn-cons">Customize</a>
                      @else
                        @if(!$active_theme['has_updates'] && auth()->user()->can('customize themes'))
                          <a href="{{ $active_theme['customize_uri'] }}" class="btn btn-complete btn-cons">Customize</a>
                        @else($active_theme['has_updates'])
                          <a href="{{ $active_theme['update_uri'] }}" class="btn btn-info btn-cons">Update {{ $active_theme['update_version'] }}</a>
                        @endif
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>    
        </div>
        @if(count($themes))
        <h4>
          <span class="semi-bold">My Themes</span>
        </h4>
        <div class="sm-no-padding">
         <div class="card card-transparent">
            <div class="card-block no-padding">
              <div class="card card-default">
                <div class="card-header">
                </div>
                <div class="card-block">
                  <div class="row">
                    @foreach($themes as $theme)
                      <div class="col-md-4">
                        <div class="card">
                          <div style="height: 230px; background: url({{ $theme['screenshot'] }})" class="img-thumbnail theme-preview"></div>
                          <div class="card-block">
                            <h5 class="card-title semi-bold">{{ $theme['name'] }}</h5>
                            <p class="lead">Version: {{ $theme['current_version'] }}</p>
                            <div class="d-flex justify-content-between">
                              <a href="#" class="btn btn-info btn-xs" onclick="Shopbox.activateTheme(event, '{{ $theme['alias'] }}')">Activate</a>
                              @if(session('store')->domain === 'demo')
                                <a href="{{ $theme['customize_uri'] }}" class="btn btn-info btn-xs">Customize</a>
                              @else
                                @if($theme['has_updates'])
                                  <a href="{{ $theme['update_uri'] }}" class="btn btn-info btn-xs">Update {{ $theme['update_version'] }}</a>
                                @elseif(!$theme['has_updates'] && auth()->user()->can('customize themes'))
                                  <a href="{{ $theme['customize_uri'] }}" class="btn btn-info btn-xs">Customize</a>
                                @endif
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>    
        </div>
        @endif
    </div>
  </div>
</form>
@endsection

