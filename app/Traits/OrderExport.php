<?php

namespace Shopbox\Traits;

use Excel;
use Modules\Order\Entities\OrderDetail;

trait OrderExport
{
    public function startExport($orders, $store_id)
    {
        set_time_limit(5000);
        $data = $this->getOrderData($orders);
        dd('here');
        Excel::create('orders', function($excel) use($data) {

            $excel->sheet('orders', function($sheet) use($data) {
    
                $sheet->fromArray($data);

            });

        })->store('csv', storage_path('exports/'.$store_id));
    }

	protected function getOrderData($orders)
    {
        $data = [];
        $index = 0;

        for ($i=0; $i < $orders->count(); $i++) { 
            $data[$index]['Order ID'] = $orders[$i]->formattedOrderId;
            $data[$index]['Customer Name'] = $orders[$i]->customer->fullName;
            $data[$index]['Customer Email'] = $orders[$i]->customer->customerEmail;
            $data[$index]['Customer Phone'] = $orders[$i]->customer->phone;
            $data[$index]['Order Date'] = $orders[$i]->created_at->toDateString();
            $data[$index]['Order Status'] = $orders[$i]->state->name;
            $data[$index]['Subtotal (inc tax)'] = $orders[$i]->total_products_wt;
            $data[$index]['Subtotal (ex tax)'] = $orders[$i]->total_products;
            $data[$index]['Tax Total'] = $orders[$i]->total_products_wt - $orders[$i]->total_products;
            $data[$index]['Shipping Cost (inc tax)'] = $orders[$i]->total_shipping_tax_incl;
            $data[$index]['Shipping Cost (ex tax)'] = $orders[$i]->total_shipping_tax_excl;
            $data[$index]['Shipping Method'] = $orders[$i]->shipper ? $orders[$i]->shipper->name : null;
            $data[$index]['Store Credit Redeemed'] = $orders[$i]->store_credits;
            $data[$index]['Discount Code'] = $orders[$i]->cart_discount ? $orders[$i]->cart_discount->discount->code : null;
            $data[$index]['Discount Amount'] = $orders[$i]->cart_discount ? $orders[$i]->cart_discount->value : 0;
            $data[$index]['Order Total (inc tax)'] = $orders[$i]->total_paid_tax_incl;
            $data[$index]['Order Total (ex tax)'] = $orders[$i]->total_paid_tax_excl;
            $data[$index]['Payment Method'] = $orders[$i]->payment_method;
            $data[$index]['Order Currency Code'] = $orders[$i]->payment->order_currency;
            $data[$index]['Order Remarks'] = $orders[$i]->cart->message;
            $data[$index]['Billing First Name'] = $orders[$i]->billing_address->firstname;
            $data[$index]['Billing Last Name'] = $orders[$i]->billing_address->lastname;
            $data[$index]['Billing Company'] = $orders[$i]->billing_address->company;
            $data[$index]['Billing Address1'] = $orders[$i]->billing_address->address;
            $data[$index]['Billing Address2'] = $orders[$i]->billing_address->address2;
            $data[$index]['Billing City'] = $orders[$i]->billing_address->city;
            $data[$index]['Billing State'] = $orders[$i]->billing_address->state ? $orders[$i]->billing_address->state->name : null;
            $data[$index]['Billing Zip'] = $orders[$i]->billing_address->zip_code;
            $data[$index]['Billing Country'] = $orders[$i]->billing_address->country->name;
            $data[$index]['Billing Phone'] = $orders[$i]->billing_address->phone;
            $data[$index]['Shipping First Name'] = $orders[$i]->shipping_address->firstname;
            $data[$index]['Shipping Last Name'] = $orders[$i]->shipping_address->lastname;
            $data[$index]['Shipping Company'] = $orders[$i]->shipping_address->company;
            $data[$index]['Shipping Address1'] = $orders[$i]->shipping_address->address;
            $data[$index]['Shipping Address2'] = $orders[$i]->shipping_address->address2;
            $data[$index]['Shipping City'] = $orders[$i]->shipping_address->city;
            $data[$index]['Shipping State'] = $orders[$i]->shipping_address->state ? $orders[$i]->shipping_address->state->name : null;
            $data[$index]['Shipping Zip'] = $orders[$i]->shipping_address->zip_code;
            $data[$index]['Shipping Country'] = $orders[$i]->shipping_address->country->name;
            $data[$index]['Shipping Phone'] = $orders[$i]->shipping_address->phone;

            if($orders[$i]->details->count()) {
                $data = $this->gerOrderItems($data, $index, $orders[$i]->details->first());
                $data[$index]['Transaction Amount'] = null;
                $data[$index]['Transaction Status'] = null;
                $data[$index]['Transaction Processed At'] = null;
                $data[$index]['Transaction Gateway'] = null;

                if($orders[$i]->details->count() === 1) {
                    $data[$index]['Transaction Amount'] = $orders[$i]->payment->trans_amount;
                    $data[$index]['Transaction Status'] = $orders[$i]->total_real_paid > 0 ? 'success' : 'pending';
                    $data[$index]['Transaction Processed At'] = $orders[$i]->payment->created_at_tz->toDateString();
                    $data[$index]['Transaction Gateway'] = $orders[$i]->payment->payment_gateway;
                    $index += 1;
                } else {
                    foreach($orders[$i]->details->take(-($orders[$i]->details->count() - 1)) as $key => $item) {
                        $data[$index+1]['Order ID'] = $orders[$i]->formattedOrderId;
                        $data[$index+1]['Customer Name'] = $orders[$i]->customer->fullName;
                        $data[$index+1]['Customer Email'] = $orders[$i]->customer->customerEmail;
                        $data[$index+1]['Customer Phone'] = $orders[$i]->customer->phone;
                        $data[$index+1]['Order Date'] = null;
                        $data[$index+1]['Order Status'] = null;
                        $data[$index+1]['Subtotal (inc tax)'] = null;
                        $data[$index+1]['Subtotal (ex tax)'] = null;
                        $data[$index+1]['Tax Total'] = null;
                        $data[$index+1]['Shipping Cost (inc tax)'] = null;
                        $data[$index+1]['Shipping Cost (ex tax)'] = null;
                        $data[$index+1]['Shipping Method'] = null;
                        $data[$index+1]['Store Credit Redeemed'] = null;
                        $data[$index+1]['Discount Code'] = null;
                        $data[$index+1]['Discount Amount'] = null;
                        $data[$index+1]['Order Total (inc tax)'] = null;
                        $data[$index+1]['Order Total (ex tax)'] = null;
                        $data[$index+1]['Payment Method'] = null;
                        $data[$index+1]['Order Currency Code'] = null;
                        $data[$index+1]['Order Remarks'] = null;
                        $data[$index+1]['Billing First Name'] = null;
                        $data[$index+1]['Billing Last Name'] = null;
                        $data[$index+1]['Billing Company'] = null;
                        $data[$index+1]['Billing Address1'] = null;
                        $data[$index+1]['Billing Address2'] = null;
                        $data[$index+1]['Billing City'] = null;
                        $data[$index+1]['Billing State'] = null;
                        $data[$index+1]['Billing Zip'] = null;
                        $data[$index+1]['Billing Country'] = null;
                        $data[$index+1]['Billing Phone'] = null;
                        $data[$index+1]['Shipping First Name'] = null;
                        $data[$index+1]['Shipping Last Name'] = null;
                        $data[$index+1]['Shipping Company'] = null;
                        $data[$index+1]['Shipping Address1'] = null;
                        $data[$index+1]['Shipping Address2'] = null;
                        $data[$index+1]['Shipping City'] = null;
                        $data[$index+1]['Shipping State'] = null;
                        $data[$index+1]['Shipping Zip'] = null;
                        $data[$index+1]['Shipping Country'] = null;
                        $data[$index+1]['Shipping Phone'] = null;
                        $data = $this->gerOrderItems($data, $index+1, $item);

                        if($key === $orders[$i]->details->count() - 1) {
                            $data[$index+1]['Transaction Amount'] = $orders[$i]->payment->trans_amount;
                            $data[$index+1]['Transaction Status'] = $orders[$i]->total_real_paid > 0 ? 'success' : 'pending';
                            $data[$index+1]['Transaction Processed At'] = $orders[$i]->payment->created_at_tz->toDateString();
                            $data[$index+1]['Transaction Gateway'] = $orders[$i]->payment->payment_gateway;
                        }

                        $index += 2;
                    }
                }
            }
            
        }

        return array_values($data);

    }

    protected function gerOrderItems($data, $index, OrderDetail $item)
    {
        $data[$index]['Lineitem name'] = $item->product_name;
        $data[$index]['Lineitem quantity'] = $item->product_quantity;
        $data[$index]['Lineitem price'] = $item->unit_price;
        $data[$index]['Lineitem cost price'] = $item->original_cost_price;
        $data[$index]['Lineitem sku'] = $item->product_sku;

        return $data;
    }
}