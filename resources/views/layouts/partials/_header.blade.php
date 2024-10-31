
<div class="header ">
<!-- START MOBILE SIDEBAR TOGGLE -->
<a href="#" class="btn-link toggle-sidebar hidden-lg-up pg pg-menu" data-toggle="sidebar">
</a>
<!-- END MOBILE SIDEBAR TOGGLE -->
<div class="">
  <div class="brand inline   ">
    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" data-src="{{ asset('assets/img/logo.png') }}" data-src-retina="{{ asset('assets/img/logo_2x.png') }}" width="78" height="22">
  </div>  
</div>
<div class="d-flex align-items-center">
  
  <!-- START User Info-->
  <!--<div class="pull-left p-r-10 fs-14 font-heading hidden-md-down">
    <span class="semi-bold">{{ auth()->user()->first_name.' '.auth()->user()->last_name }}</span>
  </div>-->
  <div class="dropdown pull-right hidden-md-down">
    <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="thumbnail-wrapper d32 circular inline">
        <span class="user-avatar{{ auth()->user()->avatar ? '' : ' user-avatar--style-2' }}">
            @if(auth()->user()->avatar)
            <img class="user-avatar__gravatar-image" alt="{{ auth()->user()->first_name }}" src="{{ asset('stores').'/'.session('store')->domain.'/img/'.auth()->user()->avatar }}">
            @endif
            <span class="user-avatar__initials text-uppercase">{{ substr(auth()->user()->first_name, 0, 1).substr(auth()->user()->last_name, 0, 1) }}</span>
        </span>
      </span>
    </button>
    <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
      <!--<a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
      <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
      <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a>-->
      
		<div class="media col-md-12">
		  <div class="media-body text-center">
			<p class="mt-2"><a href="{{ route('account.profile.index') }}">View Profile</a></p>
			
		  </div>
		</div>
		
      <a href="{{ route('admin.logout') }}" class="clearfix bg-master-lighter dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <span class="pull-left">Logout</span>
        <span class="pull-right"><i class="pg-power"></i></span>
      </a>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
      </form>
    </div>
  </div>
  <!-- END User Info-->

</div>
</div>