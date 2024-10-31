<div class="row my-2 text-center">
  <div class="col-sm-12">
    @if($duration != 0 && $duration <= 7)
      @if(session('store')->plan->slug == 'trial')
      <p>You have <b>{{ $duration }}</b> {{ $duration > 1 ? 'days' : 'day' }} left in your trial</p>
      <a href="{{ route('plan.change.index') }}" class="btn btn-action-add btn-xs">Choose Plan</a>
      @else
      <p>Your subscription will expire in <b>{{ $duration }}</b> {{ $duration > 1 ? 'days' : 'day' }}</p>
      <a href="{{ route('plan.renew') }}" class="btn btn-action-add btn-xs">Renew Plan</a>
        <form id="plan-renew" action="{{ route('plan.renew') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form> 
      @endif
    @elseif($current_date->greaterThan(session('store')->expiry_date))
      <p>You are running on <b>grace period</b>. Your account will expire on {{ session('store')->expiry_date->addDays(session('store')->grace_period)->format('l jS \\of F Y') }}</p>
      @if(session('store')->plan->slug == 'trial') 
        <a href="{{ route('plan.change.index') }}" class="btn btn-action-add btn-xs">Choose Plan</a>
      @else
        <a href="{{ route('plan.renew') }}" class="btn btn-action-add btn-xs">Renew Plan</a>          
      @endif
    @endif
  </div>
</div>
