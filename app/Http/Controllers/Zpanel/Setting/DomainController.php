<?php

namespace Shopbox\Http\Controllers\Zpanel\Setting;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Http\Requests\Domain\DomainRequest;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('zpanel.settings.domain.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DomainRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DomainRequest $request)
    {
        if(!$request->ajax()) {
            return redirect()->route('settings.domain.index');
        }

        //exec('sudo '.app_path('virtual-host-script.sh').' '.$request->domain);

        $request->tenant()->store_url = $request->domain;
        $request->tenant()->main = 1;
        $request->tenant()->client()->update([
            'redirect' => 'https://'.$request->domain.'/shopboxpay/response',
            'merchant_redirect' => 'https://'.$request->domain.'/sbpay/merchant/response'
        ]);
        $request->tenant()->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DomainRequest $request)
    {
        if(!$request->ajax()) {
            return redirect()->route('settings.domain.index');
        }

        $data = dns_get_record($request->domain, DNS_A);
		
		$data = array_where($data, function ($value, $key) use ($request) {
			if($value['type'] == 'A' && $value['host'] == $request->domain) {
				return $value;
			}
		});

        $status = false;
        $ip = $host = 'n/a';

        if(count($data)) {

            if($data[0]['ip'] == config('domain.ip')) {
                $status = true;
            }

            $ip = $data[0]['ip'];
            $host = $data[0]['host'];

        }

        return response()->json([
            'status' => $status,
            'host' => $host,
            'dns_ip' => $ip,
            'required_ip' => config('domain.ip'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DomainRequest $request)
    {
        //exec('sudo certbot --apache -n -d '.$request->domain);

        return response()->json([
            'url' => 'https://'.$request->domain
        ]);
    }

}
