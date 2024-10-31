<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Entities\Transfer;
use Modules\Product\Entities\StoreLocation;

class TransferTransformer extends TransformerAbstract
{

	public function transform(Transfer $transfer)
	{
		
		return [
			'reference' => $transfer->reference,
			'created_at' => $transfer->created_at_tz->toDayDateTimeString(),
			'from' => $this->getStoreInfo($transfer),
			'to' => $this->getStoreLocationInfo($transfer),
			'stocks' => $this->getTransferStocks($transfer)
		];
	}

	protected function getStoreInfo(Transfer $transfer) 
	{
		
		$data['store'] = $transfer->store->store_name;
		$data['address'] = $this->getStoreAddress($transfer->store);
		$data['phone'] = $transfer->store->phone;

		return $data;
	}

	protected function getStoreLocationInfo(Transfer $transfer) 
	{
		
		$data['store'] = $transfer->store_location->name;
		$data['address'] = $this->getStoreLocationAddress($transfer->store_location);
		$data['phone'] = $transfer->store_location->phone;

		return $data;
	}

	protected function getStoreAddress(Store $store)
	{
		$address = '';

		if($store->address1 != '') {
			$address .=  $store->address1.', ';
		}
		
		if($store->address2 != '') {
			$address .=  $store->address2.', ';
		}

		if($store->city != '') {
			$address .= $store->city.', ';
			if($store->state) {
				if($store->state->iso_code != '') {
					$address .= $store->state->iso_code.', ';
				} else {
					$address .= $store->state->name.', ';
				}
			}		
		}

		if($store->postcode) {
			$address .= $store->postcode.', ';
		}

		$address .= $store->country->name;


		return $address;
	}

	protected function getStoreLocationAddress(StoreLocation $store)
	{
		$address = '';

		if($store->address != '') {
			$address .=  $store->address.', ';
		}
		
		if($store->city != '') {
			$address .= $store->city.', ';
			if($store->state) {
				if($store->state->iso_code != '') {
					$address .= $store->state->iso_code.', ';
				} else {
					$address .= $store->state->name.', ';
				}
			}		
		}

		if($store->postcode) {
			$address .= $store->postcode.', ';
		}

		$address .= $store->country->name;


		return $address;
	}

    protected function getTransferStocks(Transfer $transfer) 
    {

        $data = [];

        if($transfer->stocks->count()) {
            foreach($transfer->stocks as $key => $item) {
            	$data[$key]['image'] = $this->getImage($item->stock->product);
                $data[$key]['name'] = $item->stock->product->sku ? $item->stock->product->sku.' - '.$item->stock->product->name : $item->stock->product->name;
                $data[$key]['qty'] = $item->quantity;
                $data[$key]['price'] = $item->price;
            }
            return $data;
        }
        
        return $data;
        
    }

    protected function getImage($product) 
	{
		
		if($product->image()) {
			return asset('stores').'/'.session('store')->domain.'/product/'.$product->image();
		}

		return asset('assets/img/ProductDefault.gif');
	
	}
	
}