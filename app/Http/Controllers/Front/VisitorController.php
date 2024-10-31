<?php

namespace Shopbox\Http\Controllers\Front;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\StoreVisit;
use Shopbox\Models\Zpanel\Country;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class VisitorController extends Controller
{
   protected $geoip;

   public function __construct()
   {
      $this->geoip = geoip()->getLocation(geoip()->getClientIP());
   }

   public function index(Request $request)
   {
      $dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);
      $dd->parse();
      $client_info = $dd->getClient();
      $os_info = $dd->getOs();

   	$uniq_token = $request->cookie('uniq_token');
		$visit_token = $request->cookie('visit_token');

		if(Store::where('id', $request->store_id)->count()) {
			
			$store = Store::where('id', $request->store_id)->first();

			if(!$store->visits()->where('uniq_token', $uniq_token)->where('visit_token', $visit_token)->count()) {

				$visit = new StoreVisit;
				$visit->store()->associate($store);
				$visit->uniq_token = $uniq_token;
				$visit->visit_token = $visit_token;
            $visit->browser = $client_info['name'];
            $visit->browser_version = $client_info['version'];
            $visit->device = $dd->getDeviceName();
            $visit->platform = $os_info['name'];
            $visit->platform_version = $os_info['version'];

            if(Country::where('iso_code', $this->geoip->iso_code)->count()) {

               $country = Country::where('iso_code', $this->geoip->iso_code)->first();
               $visit->country()->associate($country);

            }

            $visit->state = $this->geoip->state_name;
            $visit->city = $this->geoip->city;
            $visit->ip_address = $this->geoip->ip;
				$visit->created_at = $visit->freshTimestamp();
				$visit->save();
			}
		}

   	return view('blank');
   }
}
