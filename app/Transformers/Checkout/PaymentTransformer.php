<?php

namespace Shopbox\Transformers\Checkout;

use Carbon\Carbon;
use Modules\Product\Entities\Cart;
use Shopbox\Models\Zpanel\StorePayment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
	public function transform(StorePayment $payment)
	{
		if($payment->plugin->alias !== 'cashondelivery' && $payment->plugin->alias !== 'payinstore') { 

			if($payment->shopbox_ipg && $payment->payments()->where('live', 1)->where('active', 1)->count()) {
				return [
					'id' => $payment->id,
					'alias' => $payment->plugin->alias,
					'logo' => $this->getPaymentOptionLogo($payment),
					'config' => [
						'name' => $payment->display_name
					],
					'type' => $payment->plugin->manual_payment ? 'offline' : 'api'
				];
			} else if($payment->shopbox_ipg === 0) {
				return [
					'id' => $payment->id,
					'alias' => $payment->plugin->alias,
					'logo' => $this->getPaymentOptionLogo($payment),
					'config' => [
						'name' => $payment->display_name
					],
					'type' => $payment->plugin->manual_payment ? 'offline' : 'api'
				];
			}
			
		} else {

			$cart = Cart::where('reference', request()->cookie('cart'))->first();

			if($cart && $cart->carrier && $cart->carrier->type !== 'shipping_storepickup' && $payment->plugin->alias !== 'payinstore') {

				return [
					'id' => $payment->id,
					'alias' => $payment->plugin->alias,
					'logo' => $this->getPaymentOptionLogo($payment),
					'config' => [
						'name' => $payment->display_name
					],
					'type' => 'offline'
				];

			} else if($cart && $cart->carrier && $cart->carrier->type === 'shipping_storepickup' && $payment->plugin->alias === 'payinstore') {

				return [
					'id' => $payment->id,
					'alias' => $payment->plugin->alias,
					'logo' => $this->getPaymentOptionLogo($payment),
					'config' => [
						'name' => $payment->display_name
					],
					'type' => 'offline'
				];

			}

		}

		return [];
	}

	protected function getPaymentOptionLogo(StorePayment $payment)
	{
		$logo = '';

		if($payment->plugin->logo) {
			$logo = asset('modules/'.$payment->plugin->alias.'/'.$payment->plugin->logo);
		}

		return $logo;
	}
	
}