<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Shopbox\Http\Requests\Permalink\PermalinkFormRequest;
use Shopbox\Http\Controllers\Controller;

class PermalinkController extends Controller
{
    protected $handle;

    public function index(PermalinkFormRequest $request)
    {

        $entity = $this->getEntity($request->entity);

        if(!$entity) {
            return;
        } 

        $this->handle = $request->handle;

        do {
            $this->handle = $this->generateHandle($entity, $this->handle);
        } while ($this->checkHandleAvailability($entity, $this->handle));

        return response()->json([
            'url' => $this->handle
        ]);
    }

    protected function getEntity($entity)
    {
        if($entity === 'category') {
            return 'categories';
        } else if($entity === 'blog') {
            return 'blogs';
        } else if($entity === 'page') {
            return 'pages';
        }
    }

    protected function generateHandle($entity, $handle) 
    {
        if(DB::table($entity)->where('store_id', session('store')->id)->where('slug', $handle)->count()) {
            $index = 1;
            $data = explode('-', DB::table($entity)->where('store_id', session('store')->id)->where('slug', $handle)->first()->slug);
            
            if(is_numeric(last($data))) {
                $index = (int) last($data) + 1;
                array_pop($data);
            }

            array_push($data, $index);

           return str_slug(implode('-', $data), '-');
        }

        return str_slug($handle, '-');

    }

    protected function checkHandleAvailability($entity, $handle) 
    {
        if(DB::table($entity)->where('store_id', session('store')->id)->where('slug', $handle)->count()) {
                return true;
        }

        return false;
    }

}