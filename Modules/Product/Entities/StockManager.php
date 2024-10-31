<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;
use Jenssegers\Agent\Agent;
use Modules\Product\Entities\Stock;
use Modules\Product\Entities\StockTransfer;
use Modules\Product\Entities\StockTransferReason;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Models\Zpanel\Store;

class StockManager extends Model
{
    use ForTenants;

    protected $fillable = [];

    protected $agent;

    public function __construct()     
    {
        $this->agent = new Agent();
    }

    public function saveTransfer($product_id, $product_attribute_id, $deltaQuantity, $params = array())
    {
        if ($deltaQuantity != 0) {
            $stockTransfer = $this->prepareTransfer($product_id, $product_attribute_id, $deltaQuantity, $params);

            if ($stockTransfer) {
                return true;
            }
        }

        return false;
    }

    private function prepareTransfer($product_id, $product_attribute_id, $deltaQuantity, $params = array())
    {
        $product = Product::where('id',$product_id)->first();
        $store = Store::find(session()->get('tenant'));
        $deltaQuantity >= 1 ? 1 : -1;
        if ($product->id) {
            $stock = $this->getStockAvailableByProduct($product, $product_attribute_id);

            if ($stock->id) {
                $stock_transfer = new StockTransfer();
                $stock_transfer->reason()->associate($deltaQuantity >= 1 ? StockTransferReason::find(1) : StockTransferReason::find(2));
                if(!empty($params['store_to']) && $params['store_to'] !== 0) {
                    $stock_transfer->storeTo()->associate((int)$params['store_to']);
                }
                if(!empty($params['store_from']) && $params['store_from'] !== 0) {
                    $stock_transfer->storeFrom()->associate((int)$params['store_from']);
                }
                $stock_transfer->stock()->associate($stock->id);

                if (!empty($params['order_id'])) {
                    // need to creare relationship.
                    $stock_transfer->order_id = ((int)$params['order_id']);
                }

                if (!empty($params['stock_tranfer_reason_id'])) {
                    $stock_transfer->reason()->associate((int)$params['stock_tranfer_reason_id']);
                }

                $stock_transfer->sign = $deltaQuantity >= 1 ? 1 : -1;
                $stock_transfer->quantity = abs($deltaQuantity);
                $stock_transfer->user()->associate(auth()->user());
                $stock_transfer->user_firstname = auth()->user()->first_name;
                $stock_transfer->user_lastname = auth()->user()->last_name;
                $stock_transfer->ip_address = Track::getRealIpAddr(); 
                $stock_transfer->browser = $this->agent->browser(); 
                $stock_transfer->platform = $this->agent->platform(); 
                $stock_transfer->created_at_tz = $stock_transfer->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $stock_transfer->updated_at_tz = $stock_transfer->freshTimestamp()->timezone($store->setting->timezone->timezone);
                $stock_transfer->save();
            }
        }

        return false;
    }

    /**
     * Gets available stock for a given product / combination / shop.
     *
     * @param object $product
     * @param null $id_product_attribute
     * @param null $id_shop
     * @return StockAvailable
     */
    public function getStockAvailableByProduct(Product $product, $product_attribute_id = null, $id_shop = null)
    {
        $stock = Stock::where('id', Stock::getStockAvailableIdByProductId($product->id, $product_attribute_id, $id_shop))->first();

        if (!$stock->id) {
            $stock->product()->associate($product);
            $stock->productAttribute()->associate($product_attribute_id);

            $outOfStock = $this->outOfStock((int)$product->id, $id_shop);
            $stock->out_of_stock = (int)$outOfStock;
            $stock->save();
        }

        return $stock;
    }

    /**
     * For a given product, get its "out of stock" flag
     *
     * @param int $productId
     * @param int $shopId Optional : gets context if null @see Context::getContext()
     * @return bool : depends on stock @see $depends_on_stock
     */
    public function outOfStock($product_id, $store_idl)
    {
        return Stock::outOfStock($productId, $shopId);
    }
}
