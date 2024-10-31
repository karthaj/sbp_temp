<?php

namespace Shopbox\Http\Controllers\Front\Wishlist;

use Shopbox\Models\Wishlist;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Http\Requests\StoreFront\WishlistFormRequest;

class WishlistController extends Controller
{
    
    public function store(WishlistFormRequest $request)
    {
        if(!session('store')->products()->where('id', $request->product_id)->count()) {
             exit('Product not found.');
        }

        if(!auth()->user()->wishlists()->where('store_id', session('store')->id)->where('product_id', $request->product_id)->count()) {
            $wishlist = new Wishlist;
            $wishlist->customer()->associate(auth()->user()); 
            $wishlist->store()->associate(session('store'));
            $wishlist->product()->associate($request->product_id);   
            $wishlist->save();
        }
    }

    public function destroy(WishlistFormRequest $request)
    {
        if(!auth()->user()->wishlists()->where('store_id', session('store')->id)->where('product_id', $request->product_id)->count()) {
            exit('Product not found.');
        }

        auth()->user()->wishlists()->where('store_id', session('store')->id)->where('product_id', $request->product_id)->delete();
    }

}
