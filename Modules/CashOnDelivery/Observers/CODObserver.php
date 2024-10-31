<?php

namespace Modules\CashOnDelivery\Observers;

use Modules\CashOnDelivery\Entities\COD;
use Jenssegers\Agent\Agent;
use Shopbox\Models\Zpanel\Track;

class CODObserver
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function creating(COD $cod)
    {
        $cod->setAttribute('created_at_tz', $cod->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $cod->setAttribute('updated_at_tz', $cod->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $cod->setAttribute('created_by', auth()->user()->email);
        $cod->setAttribute('updated_by', auth()->user()->email);
        $cod->setAttribute('browser', $this->agent->browser());
        $cod->setAttribute('ip_address', Track::getRealIpAddr());
        $cod->setAttribute('platform', $this->agent->platform());
    }

    public function updating(COD $cod)
    {
        $cod->setAttribute('updated_at_tz', $cod->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $cod->setAttribute('updated_by', auth()->user()->email);
        $cod->setAttribute('browser', $this->agent->browser());
        $cod->setAttribute('ip_address', Track::getRealIpAddr());
        $cod->setAttribute('platform', $this->agent->platform());
    }
}