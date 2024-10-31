<?php

namespace Modules\Order\Http\Controllers\DataTable;

use Shopbox\Traits\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Cart;
use Illuminate\Routing\Controller;
use Shopbox\Transformers\Cart\AbandonedCartTransformer;
use Shopbox\Http\Controllers\DataTable\DataTableController;

class CartController extends DataTableController
{
    public function builder() {
        return Cart::query();
    }
    
    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = $request->user()->can('delete carts');
        $this->allowUpdate = $request->user()->can('edit carts');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {

            $records = $this->builder->selectRaw('carts.id')
                ->join('cart_products', 'cart_products.cart_id', '=', 'carts.id')
                ->join('customers', 'customers.id', '=', 'carts.customer_id')
                ->leftJoin('orders', 'orders.cart_id', '=', 'carts.id')
                ->where('carts.store_id', $request->tenant()->id)
                ->where('customers.firstname', '<>', '')
                ->where('customers.lastname', '<>', '')
                ->where(function ($query) {
                    $query->whereNull('orders.cart_id')
                      ->orWhere('orders.status', 0);
                })
                ->groupBy('carts.id')
                ->orderBy('carts.id', 'desc')
                ->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return fractal()->collection($records->getCollection())->transformWith(new AbandonedCartTransformer)->toArray()['data'];

        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!request()->user()->can('delete carts')) {
            return;
        }

        $data = explode(',', $ids);

        foreach($data as $id) {

            $cart = request()->tenant()->carts()->where('id', $id)->first();

            if($cart && $cart->stock_reserved) {
                $this->restock($cart);
            }
        }
            

        $this->builder->where('store_id', request()->tenant()->id)->whereIn('id', explode(',', $ids))->delete();

    }

}
