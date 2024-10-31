@extends('layouts.response')

@section('content')
@php
    $data = $order->store->client->id.$order->store->client->secret. $order->store_id.'-'.$order->order_id.$order->total_paid * 100;
        $signature = base64_encode(pack('H*', sha1($data)));
@endphp
<div class="starter-template">
    <h1>Connecting to Shopbox Pay...</h1>
    <form id="shopboxpay" action="{{ env('SHOPBOXPAY_URL') }}" method="post">
      <input type="hidden" name="client_id" value="{{ $order->store->client->id }}">
      <input type="hidden" name="secret" value="{{ $order->store->client->secret }}">
      <input type="hidden" name="amount" value="{{ $order->total_paid * 100 }}">
      <input type="hidden" name="currency_code" value="{{ $order->currency->iso_code }}">
      <input type="hidden" name="order_id" value="{{ $order->store_id }}-{{ $order->order_id }}">
      <input type="hidden" name="signature" value="{{ $signature }}">
      <input type="hidden" name="customer_firstname" value="{{ $order->billing_address->firstname }}">
      <input type="hidden" name="customer_lastname" value="{{ $order->billing_address->lastname }}">
      <input type="hidden" name="customer_email" value="{{ $order->customer->customerEmail }}">
      <input type="hidden" name="customer_phone" value="{{ $order->billing_address->phone }}">
      <input type="hidden" name="customer_address_line_1" value="{{ $order->billing_address->address }}">
      <input type="hidden" name="customer_address_line_2" value="{{ $order->billing_address->address2 }}">
      <input type="hidden" name="customer_city" value="{{ $order->billing_address->city }}">
      <input type="hidden" name="customer_country" value="{{ $order->billing_address->country->iso_code }}">
      @if($order->billing_address->state_id)
        <input type="hidden" name="customer_state" value="{{ $order->billing_address->state->iso_code }}"
      @endif>
      <input type="hidden" name="customer_postcode" value="{{ $order->billing_address->zip_code }}">
      <input type="hidden" name="shipping_firstname" value="{{ $order->shipping_address->firstname }}">
      <input type="hidden" name="shipping_lastname" value="{{ $order->shipping_address->lastname }}">
      <input type="hidden" name="shipping_phone" value="{{ $order->shipping_address->phone }}">
      <input type="hidden" name="shipping_address_line_1" value="{{ $order->shipping_address->address }}">
      <input type="hidden" name="shipping_address_line_2" value="{{ $order->shipping_address->address2 }}">
      <input type="hidden" name="shipping_city" value="{{ $order->shipping_address->city }}">
      <input type="hidden" name="shipping_country" value="{{ $order->shipping_address->country->iso_code }}">
      @if($order->shipping_address->state_id)
        <input type="hidden" name="shipping_state" value="{{ $order->shipping_address->state->iso_code }}"
      @endif>
      <input type="hidden" name="shipping_postcode" value="{{ $order->shipping_address->zip_code }}">
      <input type="hidden" name="item_count" value="{{ $order->details->count() }}">
      @foreach($order->details as $detail)
        <input type="hidden" name="item_name{{ $loop->index }}" value="{{ str_limit($detail->product_name, 255) }}">  
        <input type="hidden" name="item_sku{{ $loop->index }}" value="{{ str_limit($detail->product_sku, 255) }}">
        <input type="hidden" name="item_unit_amount{{ $loop->index }}" value="{{ $detail->unit_price * 100 }}">
        <input type="hidden" name="item_quantity{{ $loop->index }}" value="{{ $detail->product_quantity }}">
      @endforeach
      <input type="hidden" name="shipping_cost" value="{{ $order->total_shipping * 100 }}">
      <input type="hidden" name="tax_total" value="{{ ($order->total_paid_tax_incl - $order->total_paid_tax_excl) * 100 }}">
      <input type="hidden" name="discount" value="{{ $order->total_discounts * 100 }}">
    </form>
</div>
@endsection

@section('scripts')
    <script>
      $(document).ready(function(){
          $("#shopboxpay").submit();
      });
    </script>
@endsection
