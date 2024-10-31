<?php

namespace Modules\Product\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Modules\Product\Traits\StockTrait;
use Modules\Product\Http\Requests\Stock\StockFormRequest;
use Modules\Product\Http\Requests\Stock\StockTransferFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Stock;
use Modules\Product\Entities\StoreStock;
use Modules\Product\Entities\StockManager;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Helper;
use Modules\Product\Entities\Transfer;
use Modules\Product\Transformers\StockTransformer;

class StockController extends Controller
{
    use StockTrait;

    protected $agent;

    public function __construct()     
    {
        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $store_locations = $request->tenant()->storeLocations()->where('active', 1)->get();
        $product_image = asset('stores').'/'.$request->tenant()->domain.'/product/';
        return view('product::stocks.index', compact('store_locations', 'product_image'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::stocks.create');
    }

    public function store(Request $request)
    {
        $stocks = $request->stocks;
        foreach($stocks as $stock) {
            $this->addStock($stock);
        }
    }

    public function createTransfer(Request $request)
    {
        $stores = $request->tenant()->storeLocations()->where('active', 1)->get();
        return view('product::stocks.transfer', compact('stores'));
    }

    public function search(Request $request)
    {
        $products =  Product::search($request->q)->get();

        return $this->getProductSearchResults($products);

    }

    protected function getProductSearchResults(Collection $products)
    {
        $data = collect([]);

        if($products->count()) {

            foreach($products as $product) {

                if($product->variations->count()) {

                    foreach ($product->variations as $variation) {
                        
                        $data->push([
                            'id' => $variation->stock->id,
                            'name' => $this->getVariation($variation),
                            'qty' => $this->getStock($variation->product_id, $variation->id)
                        ]);

                    }

                } else {

                    $data->push([
                        'id' => $product->stock->id,
                        'name' => $product->name,
                        'qty' => $this->getStock($product->id)
                    ]);

                }
            }

        }

        return $data;
    }

    public function getProduct(Request $request)
    {
        $stock = $request->tenant()->stocks()->where('id', $request->stock_id)->first();

        $stock_transformer = fractal()->item($stock)->transformWith(new StockTransformer)->toArray();

        $stock = $stock_transformer['data'];

        return response()->json(compact('stock'));

    }


    public function transfer(StockTransferFormRequest $request)
    {
        $transfer = new Transfer;
        $transfer->store()->associate($request->tenant());
        $transfer->reference = str_random(10);
        $transfer->type = $request->transfer_type;
        $transfer->stock_request = 0;
        $transfer->remarks = $request->remarks;
        $store = $request->tenant()->storeLocations()->where('id', $request->transfer_store)->first();
        if(!empty($store)) {
            $transfer->store_location()->associate($store);
        } 

        $transfer->save();

        foreach ($request->stocks as $stock) {
            $this->saveStockTransfer($transfer, $request->transfer_store, $stock);
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Stock $stock)
    {
        $total_stock = $stock->available_quantity;
        $store_locations = StoreLocation::where('store_id', auth()->user()->stores()->first()->id)->get();

        if($stock->storeStocks->count()) {
            $total_stock += $stock->storeStocks->sum('quantity');
        }

        return view('product::stocks.show', compact('stock','store_locations','total_stock'));
    }


}
