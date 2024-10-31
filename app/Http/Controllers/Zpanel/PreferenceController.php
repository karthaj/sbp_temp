<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Requests\Store\PreferenceRequest;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Http\Controllers\Controller;

class PreferenceController extends Controller
{
    public function index()
    {
    	$store = Store::find(session('tenant'));
    	return view('zpanel.preferences.index', compact('store'));
    }

    public function update(PreferenceRequest $request, Store $store)
    {
  
        $store->setting->meta_title = $request->page_title;
        $store->setting->meta_keywords = $request->meta_keywords;
        $store->setting->meta_description = $request->meta_description;
        $store->setting->google_analytics = $request->google_analytics;
        $store->setting->facebook_pixel_id = $request->facebook_pixel_id;
        $store->setting->captcha_site_key = $request->captcha_site_key;
        $store->setting->captcha_secret_key = $request->captcha_secret_key;
        $store->setting->password = $request->password;
        $store->setting->password_hash = bcrypt($request->password);
        $store->setting->message = $request->message;
        $store->setting->enable_password = $request->enable_password ?: 0;
        $store->setting->save();

        return redirect()->back()->withSuccess('updated successfully.');

    }
}
