@php
  $config = $order->store->configurations;
  $config = $config->pluck('value', 'name');
  $config = $config->all();
  $pass = $config['HNB_MERCHANT_PASSWORD'].$config['HNB_MERCHANT_ID'].$config['HNB_ACQUIRER_ID'].$order->order_id.str_pad($order->total_paid / $order->total_paid * 100, 12, '0', STR_PAD_LEFT).$config['HNB_CURRENCY'];
                $enc = base64_encode(pack('H*', sha1($pass)));

@endphp
@extends('layouts.response')

@section('content')
<div class="starter-template">
    <h1>Connecting to HNB...</h1>
    <form id="hnbpay" action="https://www.hnbpg.hnb.lk/SENTRY/PaymentGateway/Application/ReDirectLink.aspx" method="POST">
      <input type="hidden" name="Version" value="{{ $config['HNB_VERSION'] }}">
      <input type="hidden" name="MerID" value="{{ $config['HNB_MERCHANT_ID'] }}">
      <input type="hidden" name="AcqID" value="{{ $config['HNB_ACQUIRER_ID'] }}">
      <input type="hidden" name="MerRespURL" value="{{ getStoreUrl($order->store).'/hnb/response' }}">
      <input type="hidden" name="PurchaseCurrency" value="{{ $config['HNB_CURRENCY'] }}">
      <input type="hidden" name="PurchaseCurrencyExponent" value="2">
      <input type="hidden" name="OrderID" value="{{ $order->order_id }}">
      <input type="hidden" name="SignatureMethod" value="{{ $config['HNB_SIGNATURE_METHOD'] }}">
      <input type="hidden" name="Signature" value="{{ $enc }}">
      <input type="hidden" name="CaptureFlag" value="{{ $config['HNB_CAPTURE_FLAG'] }}">
      <input type="hidden" name="TestFlag" value="{{ $config['HNB_TEST_MODE'] }}">
      <input type="hidden" name="PurchaseAmt" value="{{ str_pad($order->total_paid / $order->total_paid * 100, 12, '0', STR_PAD_LEFT) }}">
      <input type="hidden" name="OrdersDetails" value="Y">
      <input type="hidden" name="customerid" value="{{ $order->customer->id }}">
      <input type="hidden" name="IsItemised" value="Y">
      <input type="hidden" name="NoOfItems" value="{{ $order->details->count() }}">

      @if($order->details->count())
        @foreach($order->details as $detail)
          <input type="hidden" name="ItemDescription{{ $loop->iteration }}" value="{{ str_limit($detail->product_name, 40) }}">
          <input type="hidden" name="ItemQuantity{{ $loop->iteration }}" value="{{ $detail->product_quantity }}">
          <input type="hidden" name="ItemUnitPrice{{ $loop->iteration }}" value="{{ str_pad($detail->unit_price * 100, 12, '0', STR_PAD_LEFT) }}">
          <input type="hidden" name="ItemTotalPrice{{ $loop->iteration }}" value="{{ str_pad($detail->total_price * 100, 12, '0', STR_PAD_LEFT) }}">
        @endforeach
      @endif
      <input type="hidden" name="ShippingCost" value="{{ str_pad($order->total_shipping * 100, 12, '0', STR_PAD_LEFT) }}">
    </form>
</div>
@endsection

@section('scripts')
    <script>
      $(document).ready(function(){
          $("#hnbpay").submit();
      });
    </script>
@endsection
