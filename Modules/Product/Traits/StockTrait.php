<?php

namespace Modules\Product\Traits;

use Shopbox\Models\Zpanel\Track;
use Modules\Product\Entities\Stock;
use Modules\Product\Entities\StockTransfer;
use Modules\Product\Entities\StockTransferReason;
use Modules\Product\Entities\Transfer;
use Modules\Product\Entities\TransferStock;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Entities\StoreStock;
use Modules\Product\Entities\UnsellableStock;
use Modules\Product\Entities\ProductAttribute;
use Modules\Order\Entities\Order;


trait StockTrait
{

    public function restockOrder(Order $order)
    {
        $params['reason'] = StockTransferReason::where('name', 'Stock out')->first();
        $params['entity'] = $order->store->onlineStore;
        $params['order_id'] = $order->id;
        $params['system_remark'] = $order->store->onlineStore->name.' -> stock allocated for order #'.$order->id;

        foreach ($order->details as $item) {
            $stock = $item->product->stock;

            if($item->product_attribute) {
                $stock = $item->product_attribute->stock;
            }

            $data = $this->decrementStock($stock, $item->product_quantity, $order->store->onlineStore);
            $this->saveTransfer($stock, $data['delta_quantity'], $data['balance'], $params);
        }
    }

    public function unstockOrder(Order $order)
    {
        $params['reason'] = StockTransferReason::where('name', 'Stock in')->first();
        $params['entity'] = $order->store->onlineStore;
        $params['order_id'] = $order->id;
        $params['system_remark'] = $order->store->onlineStore->name.' <- stock removed from order #'.$order->id;

        foreach ($order->details as $item) {
            $stock = $item->product->stock;

            if($item->product_attribute) {
                $stock = $item->product_attribute->stock;
            }

            $balance = $this->incrementStock($stock, $item->product_quantity, $order->store->onlineStore);
            $this->saveTransfer($stock, $item->product_quantity, $balance, $params);
        }
    }

    public function adjustStocks(Order $order)
    {
        $params['reason'] = StockTransferReason::where('name', 'Online sales')->first();
        $params['entity'] = $order->store->onlineStore;
        $params['order_id'] = $order->id;
        $params['system_remark'] = $order->store->onlineStore->name.' ->';

        foreach ($order->details as $item) {
            $stock = $item->product->stock;

            if($item->product_attribute) {
                $stock = $item->product_attribute->stock;
            }

            $data = $this->decrementStock($stock, $item->product_quantity, $order->store->onlineStore);
            $this->saveTransfer($stock, $data['delta_quantity'], $data['balance'], $params);
        }
    }

    protected function getImage(Stock $stock) 
    {
        if($stock->productAttribute) {

            if($stock->productAttribute->image) {
                return asset('stores').'/'.session('store')->domain.'/product/'.$stock->productAttribute->image->small_default;
            }
        }

        if($stock->product->image()) {
            return asset('stores').'/'.session('store')->domain.'/product/'.$stock->product->image();
        }

        return asset('assets/img/ProductDefault.gif');
    
    }

    protected function getVariation(ProductAttribute $product_attribute) 
    {
        $variation = $product_attribute->product->name;

        foreach ($product_attribute->combinations as $index => $combination) {
            $variation .= ' -'.$combination->option->name;

            if($index < $product_attribute->combinations->count() - 1) {
                $variation .= ', ';
            }
        }

        return $variation;
    }

    protected function getStock($product_id, $product_attribute_id = null, StoreLocation $store = null)
    {
        $qty = 0;

        if(session('store')->stocks()->where('product_id', $product_id)->count()) {

            $qty = session('store')->stocks()->where('product_id', $product_id)->first()->available_quantity;
        }

        if($store) {

            $stock = '';

            if($product_id) {

                $stock = session('store')->stocks()->where('product_id', $product_id)->first();

            } 

            if($product_attribute_id) {

                $stock = session('store')->stocks()->where('product_attribute_id', $product_attribute_id)->first();

            }

            if($store->stocks->count() && $stock) {

                if($store->stocks->where('stock_id', $stock->id)->count()) {

                    $qty =  $store->stocks()->where('stock_id', $stock->id)->first()->quantity;

                }

            }
        }

        if($product_attribute_id) {

            if(session('store')->stocks()->where('product_attribute_id', $product_attribute_id)->count()) {

                $qty = session('store')->stocks()->where('product_attribute_id', $product_attribute_id)->first()->available_quantity;
            }

        }


        return $qty;
    }

	protected function addStock(array $params)
    {
        $stock = Stock::find($params['stock_id']);
   
        if(!empty($stock) && $params['quantity'] !== 0) {

            if($params['quantity'] > 0) {

                $available_quantity = $this->incrementStock($stock, $params['quantity']);
                $params['system_remark'] = '-> warehouse';
                $params['remarks'] = $params['remarks'];
                $this->saveTransfer($stock, (int)$params['quantity'], $available_quantity, $params, true);

                if(session('store')->storeLocations->count() === 1) {

                    $params['system_remark'] = 'warehouse -> '.session('store')->storeLocations->first()->name;
                    $data = $this->decrementStock($stock, $params['quantity']);
                    $this->saveTransfer($stock, $data['delta_quantity'], $data['balance'], $params);
                    $balance = $this->incrementStock($stock, $params['quantity'], session('store')->storeLocations->first());
                    $params['entity'] = session('store')->storeLocations->first()->id;
                    $params['system_remark'] = session('store')->storeLocations->first()->name.' <- warehouse';
                    $this->saveTransfer($stock, $params['quantity'], $balance, $params);

                }

            } elseif($params['quantity'] < 0) {

                if(session('store')->storeLocations->count() > 1) {

                    $available_quantity = $this->incrementStock($stock, $params['quantity']);
                    $params['system_remark'] = 'stock adjustment';
                    $params['remarks'] = $params['remarks'];
                    $this->saveTransfer($stock, (int)$params['quantity'], $available_quantity, $params, true);

                } else {

                    // return to warehouse
                    $params['system_remark'] = session('store')->storeLocations->first()->name.' -> warehouse';
                    $params['entity'] = session('store')->storeLocations->first()->id;
                    $params['remarks'] = $params['remarks'];
                    $balance = $this->incrementStock($stock, $params['quantity'], session('store')->storeLocations->first());
                    $this->saveTransfer($stock, $params['quantity'], $balance, $params);

                    // stock into warehouse
                    $available_quantity = $this->incrementStock($stock, abs($params['quantity']));
                    $params['system_remark'] = 'warehouse <- '.session('store')->storeLocations->first()->name;
                    $params['remarks'] = $params['remarks'];
                    $this->saveTransfer($stock, (int) abs($params['quantity']), $available_quantity, $params, true);

                    // stock adjust from warehouse
                    $available_quantity = $this->incrementStock($stock, $params['quantity']);
                    $params['system_remark'] = 'stock adjustment';
                    $params['remarks'] = $params['remarks'];
                    $this->saveTransfer($stock, (int)$params['quantity'], $available_quantity, $params, true);

                }
                

            }
 
        }

    }

    protected function saveStockReturn(Transfer $transfer, StoreLocation $store, $params)
    {
        $transfer_stock = new TransferStock;
        $transfer_stock->transfer()->associate($transfer);

        $stock = Stock::find($params['stock_id']);
        $transfer_stock->stock()->associate($stock);
        $transfer_stock->quantity = $params['qty'];
        $transfer_stock->created_at_tz = $transfer_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $transfer_stock->updated_at_tz = $transfer_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $transfer_stock->save();

        $data = $this->decrementStock($stock, $params['qty'], $store);
        $params['system_remark'] = $store->name.' -> warehouse';
        $params['entity'] = $store->id;
       
        $this->saveTransfer($stock, $data['delta_quantity'], $data['balance'], $params, true);

    }

    protected function saveStockTransfer(Transfer $transfer, $store_to, $params)
    {
        $transfer_stock = new TransferStock;
        $transfer_stock->transfer()->associate($transfer);

        $stock = Stock::find($params['stock_id']);

        if($stock) {
            $transfer_stock->stock()->associate($stock);
        }
        
        $transfer_stock->quantity = $params['qty'];

        if(!empty($params['discount'])) {
            $transfer_stock->discount = $params['discount'];
        }

        $transfer_stock->discount_amount = $params['total'];
        $transfer_stock->price = $params['price'];
        $transfer_stock->total = $params['qty'] * $params['price'];

        $transfer_stock->created_at_tz = $transfer_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $transfer_stock->updated_at_tz = $transfer_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $transfer_stock->save();

        $data = $this->decrementStock($stock, $params['qty']);

        if(session('store')->storeLocations()->where('id', $store_to)->count()) {
            $params['system_remark'] = 'warehouse -> '.session('store')->storeLocations()->where('id', $store_to)->first()->name;
        }     

        $this->saveTransfer($stock, $data['delta_quantity'], $data['balance'], $params, true);


    }

    protected function decrementStock(Stock $stock, $quantity, StoreLocation $store = null)
    {
        $deltaQuantity = -1 * abs($quantity);

        if($store) {
            if($store->stocks()->where('stock_id', $stock->id)->count()) {
                $store_stock = $store->stocks()->where('stock_id', $stock->id)->first();
                $balance = $store_stock->quantity;
                $store_stock->quantity -= $quantity;
                $store_stock->save();

            }
        } else {
            $balance = $stock->available_quantity;
            $stock->available_quantity -= $quantity;
            $stock->save();
        }
       
        
        
        return [
            'delta_quantity' => $deltaQuantity,
            'balance' => $balance,
        ];
    }

    protected function incrementStock(Stock $stock, $quantity, StoreLocation $store = null)
    {

        if($store) {

            if($store->stocks->contains('stock_id', $stock->id)) {

                $store_stock = $store->stocks()->where('stock_id', $stock->id)->first();
                $balance = $store_stock->quantity;
                $store_stock->quantity += $quantity;
                $store_stock->save();

            } else {
                $balance = 0;
                $store_stock = new StoreStock;
                $store_stock->store()->associate(session('store'));
                $store_stock->stock()->associate($stock);
                $store_stock->storeLocation()->associate($store);
                $store_stock->quantity = $quantity;
                $store_stock->save();
            }
            
        } else {
            $balance = $stock->available_quantity;
            $stock->available_quantity += $quantity;
            $stock->save();
        }
        
        return $balance;
    }


    protected function saveTransfer($stock, $deltaQuantity, $balance, $params, $trackUser = false)
    {
        if ($deltaQuantity != 0) {
            $this->prepareTransfer($stock, $deltaQuantity, $balance, array_filter($params), $trackUser);

        }
    }

    protected function prepareTransfer($stock, $deltaQuantity, $balance, $params, $trackUser)
    {
        $deltaQuantity >= 1 ? 1 : -1;
       
        $stock_transfer = new StockTransfer;
        $stock_transfer->store()->associate(session('store'));

        if(!empty($params['reason'])) {
            $stock_transfer->reason()->associate($params['reason']);
        } else {
            $stock_transfer->reason()->associate($deltaQuantity >= 1 ? StockTransferReason::find(1) : StockTransferReason::find(2));
        }
        
        $stock_transfer->stock()->associate($stock);

        if(!empty($params['entity'])) {

           $stock_transfer->store_location()->associate($params['entity']);
            
        }

        if (!empty($params['order_id'])) {
            $stock_transfer->order()->associate((int)$params['order_id']);
        }

        if (!empty($params['remarks'])) {
            $stock_transfer->user_remark = $params['remarks'];
        }

        if(!empty($params['system_remark'])) {
            $stock_transfer->remark = $params['system_remark'];
        }

        $stock_transfer->sign = $deltaQuantity >= 1 ? 1 : -1;
        $stock_transfer->quantity = abs($deltaQuantity);
        $stock_transfer->available_quantity = $balance;

        if($trackUser === true) {
            $stock_transfer->user()->associate(auth()->user());
            $stock_transfer->employee = auth()->user()->first_name.' '.auth()->user()->last_name;
        }
       
        $stock_transfer->ip_address = Track::getRealIpAddr(); 
        $stock_transfer->browser = $this->agent->browser(); 
        $stock_transfer->platform = $this->agent->platform(); 
        $stock_transfer->save();
        
    }

    protected function transferStock(Transfer $transfer, $params, $store = null)
    {
        foreach ($transfer->stocks as $item) {
            $balance = $this->incrementStock($item->stock, $item->quantity, $store);
            $this->saveTransfer($item->stock, $item->quantity, $balance, $params, true); 
        }
        
    }

    protected function rollbackStock(Transfer $transfer, $params, $store = null)
    {
        foreach ($transfer->stocks as $item) {
            $balance = $this->incrementStock($item->stock, $item->quantity, $store);
            $this->saveTransfer($item->stock, $item->quantity, $balance, $params, true);
        }
    }

    protected function unsellableStock(Transfer $transfer)
    {
        foreach ($transfer->stocks as $item) {
            $stock = new UnsellableStock;
            $stock->store()->associate($transfer->store);
            $stock->stock()->associate($item->stock);
            $stock->quantity = $item->quantity;

            if($transfer->type === 'damage') {
                $stock->type = 1;
            } elseif($transfer->type === 'stolen') {
                $stock->type = 0;
            }

            $stock->save();
            $params['system_remark'] = 'warehouse <- '.$transfer->store_location->name;
            $params['entity'] = $transfer->store_location_id;
            $this->saveTransfer($item->stock, $item->quantity, $item->stock->available_quantity, $params, true);
        }
    }

}