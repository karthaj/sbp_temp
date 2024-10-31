<?php

namespace Shopbox\Tenant\Observers;

use DeviceDetector\DeviceDetector;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class TenantObserver
{
	protected $tenant;
	protected $geoip;
	protected $dd;
	protected $client_info;
	protected $os_info;

	public function __construct(Model $tenant)
    {
        $this->tenant = $tenant;
        //$this->geoip = geoip()->getLocation(geoip()->getClientIP());
        $this->dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);
        $this->dd->parse();
        $this->client_info = $this->dd->getClient();
        $this->os_info = $this->dd->getOs();
    }

	public function creating(Model $model)
	{	
		$columns = Schema::getColumnListing($model->getTable());
		$foreignKey = $this->tenant->getForeignKey();


		if (!isset($model->{$foreignKey})) {
			$model->setAttribute($foreignKey, $this->tenant->id);
		}

		if((bool) array_search('created_at_tz',$columns)) {
			$model->setAttribute('created_at_tz', $model->freshTimestamp()->timezone($this->tenant->setting->timezone->timezone));
		}

		if((bool) array_search('updated_at_tz',$columns)) {
			$model->setAttribute('updated_at_tz', $model->freshTimestamp()->timezone($this->tenant->setting->timezone->timezone));
		}

		if((bool) array_search('created_by',$columns)) {
			$model->setAttribute('created_by', auth()->user()->email);
		}

		if((bool) array_search('updated_by',$columns)) {
			$model->setAttribute('updated_by', auth()->user()->email);
		}

		if((bool) array_search('browser',$columns)) {
			$model->setAttribute('browser', $this->client_info['name']);
		}

		if((bool) array_search('browser_version',$columns)) {
			$model->setAttribute('browser_version', $this->client_info['version']);
		}

		if((bool) array_search('ip_address',$columns)) {
			$model->setAttribute('ip_address', request()->ip());
		}

		if((bool) array_search('platform',$columns)) {
			$model->setAttribute('platform', $this->os_info['name']);
		}

		if((bool) array_search('platform_version',$columns)) {
			$model->setAttribute('platform_version', $this->os_info['version']);
		}

		if((bool) array_search('device',$columns)) {
			$model->setAttribute('device', $this->dd->getDeviceName());
		}
		
	}

	public function updating(Model $model)
	{
		$columns = Schema::getColumnListing($model->getTable());
		
		if((bool) array_search('updated_at_tz',$columns)) {
			$model->setAttribute('updated_at_tz', $model->freshTimestamp()->timezone($this->tenant->setting->timezone->timezone));
		}

		if((bool) array_search('updated_by',$columns)) {
			$model->setAttribute('updated_by', auth()->user()->email);
		}

		if((bool) array_search('browser',$columns)) {
			$model->setAttribute('browser', $this->client_info['name']);
		}

		if((bool) array_search('browser_version',$columns)) {
			$model->setAttribute('browser_version', $this->client_info['version']);
		}

		if((bool) array_search('ip_address',$columns)) {
			$model->setAttribute('ip_address', request()->ip());
		}

		if((bool) array_search('platform',$columns)) {
			$model->setAttribute('platform', $this->os_info['name']);
		}

		if((bool) array_search('platform_version',$columns)) {
			$model->setAttribute('platform_version', $this->os_info['version']);
		}

		if((bool) array_search('device',$columns)) {
			$model->setAttribute('device', $this->dd->getDeviceName());
		}
		
	}

}