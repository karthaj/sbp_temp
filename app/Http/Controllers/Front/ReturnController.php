<?php

namespace Shopbox\Http\Controllers\Front;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use DeviceDetector\DeviceDetector;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Entities\OrderReturn;
use Shopbox\Http\Controllers\Controller;
use Modules\Order\Entities\OrderReturnState;
use Modules\Order\Entities\OrderReturnDetail;
use Shopbox\Mail\Order\ReturnConfirmationEmail;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Shopbox\Http\Requests\StoreFront\Account\ReturnFormRequest;

class ReturnController extends Controller
{

  public function store(ReturnFormRequest $request, Order $order)
  {
    $data = array_filter($request->return_qty);

    if(!$order->store->setting->enable_returns) {
      return redirect()->back()->withInfo('Returns are not accepted by this store.');
    }

    if(!$order->store->setting->enable_partial_returns && (count($data) > 0 && count($data) < $order->details->count())) {
      return redirect()->back()->withInfo('Partial returns are not accepted by this store.');
    }

    $dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);
    $dd->parse();
    $client_info = $dd->getClient();
    $os_info = $dd->getOs();

    $return = new OrderReturn;
    $return->store()->associate(session('store'));
    $return->return_id = session('store')->returns->count() + 1;
    $return->customer()->associate($order->customer);
    $return->order()->associate($order);
    $return->status()->associate(OrderReturnState::where('name', 'Pending')->first());
    $return->reason = $request->return_reason;
    $return->created_by = $request->user()->email;
    $return->updated_by = $request->user()->email;
    $return->browser = $client_info['name'];
    $return->browser_version = $client_info['version'];
    $return->device = $dd->getDeviceName();
    $return->platform = $os_info['name'];
    $return->platform_version = $os_info['version'];
    $return->ip_address = $request->ip();
    $return->created_at_tz = $return->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
    $return->updated_at_tz = $return->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
    $return->save();

    foreach($data as $item => $qty) {

      $detail = new OrderReturnDetail;
      $detail->orderReturn()->associate($return);
      $detail->orderDetail()->associate($item);
      $detail->quantity = $qty;
      $detail->save();

    }

    Mail::to(session('store')->trans_email)->queue(new ReturnConfirmationEmail($return));
    Mail::to($return->customer)->queue(new ReturnConfirmationEmail($return));

    return redirect(getStoreUrl(session('store')).'/account?tab=return_list')->withSuccess('Your request submitted successfully.');
  }

}
