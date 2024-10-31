<?php

namespace Shopbox\Http\Controllers\Storefront\File;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\File;
use Shopbox\Http\Controllers\Controller;

class FileController extends Controller
{
    public function index(Request $request, Order $order, File $file)
    {
    	if(!$file || $file->product->store_id !== session('store')->id || $order->store_id !== session('store')->id) {
    		abort(404);
    	}

    	$item = $order->details()->where('product_id', $file->product_id)->first();

    	if(!$item) {
    		abort(404);
    	}

    	if($file->maximum_downloads == $item->download_nb) {
    		return redirect()->back();
    	}

    	$item->increment('download_nb');
    	$item->save();

		if (file_exists(storage_path('app/stores/'.session('store')->store_name.'/files/'.$file->id.'/'.$file->filename))) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename(storage_path('app/stores/'.session('store')->store_name.'/files/'.$file->id.'/'.$file->filename)).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize(storage_path('app/stores/'.session('store')->store_name.'/files/'.$file->id.'/'.$file->filename)));
		    readfile(storage_path('app/stores/'.session('store')->store_name.'/files/'.$file->id.'/'.$file->filename));
		    exit;
		}

    	// return response()->download(storage_path('app/stores/'.session('store')->store_name.'/files/'.$file->id.'/'.$file->filename));
    }
}
