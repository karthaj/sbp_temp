<?php

namespace Shopbox\Http\Controllers\Front;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Front\Theme;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Combination;
use Modules\Product\Entities\Brand;
use Shopbox\Transformers\StoreFront\ProductTransformer;
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;
use Shopbox\Transformers\StoreFront\CategoryTransformer;
use Shopbox\Transformers\StoreFront\BrandTransformer;
use Modules\Menu\Entities\Menu;
use Modules\Product\Entities\Category;
use Modules\Customer\Entities\Customer;
use Modules\Page\Entities\Page;
use Modules\Blog\Entities\Blog;
use Modules\Order\Entities\Order;
use Leafo\ScssPhp\Compiler;
use Illuminate\Support\Facades\Crypt;
use Shopbox\Http\Requests\Contact\ContactFormRequest;
use Illuminate\Support\Facades\Mail;
use Shopbox\Mail\Contact\ContactEmail;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class StoreController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return view($request->viewPath.'.home');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view($request->viewPath.'.products');

    }

    public function categoryIndex(Request $request)
    {

        return view($request->viewPath.'.categories');

    }

    public function brandIndex(Request $request)
    {

        return view($request->viewPath.'.brands');

    }

    public function blogs(Request $request)
    {
       
        return view($request->viewPath.'.blogs');

    }

    public function blog(Blog $blog, Request $request)
    {

        return view($request->viewPath.'.blog', compact('blog'));

    }

    public function showPage(Page $page, Request $request)
    {
        return view($request->viewPath.'.page', compact('page'));
    }

    public function send(ContactFormRequest $request)
    {
        $response = $this->client->post(config('services.recaptcha.site_verify'), [
            'form_params' => [
                'secret' => session('store')->setting->captcha_secret_key ?: config('services.recaptcha.secret_key'),
                'response' => $request->get('g-recaptcha-response'),
            ],
        ]);

        $response = json_decode((string) $response->getBody(), true);
   
        if(!$response['success']) {
            return redirect()->back()->withError('Token verification failed. Please try again.');
        }

        if($response['action'] === session('store')->domain.'_contactpage' || $response['score'] >= 0.5) {
            Mail::to(session('store')->store_email)->queue(new ContactEmail($request->name, $request->email, $request->subject, $request->content));
            return redirect()->back()->withSuccess('We\'ve received your message and will repond shortly.');
        }
       
        return redirect()->back();
    }

    public function show(Product $product, Request $request)
    {
        $data = fractal()->item($product->load(['stock', 'variations.stock.storeStocks', 'variations.combinations.option.attribute', 'images', 'relatedProducts.variations.stock.storeStocks', 'relatedProducts.stock.storeStocks']))->transformWith(new ProductTransformer)->toArray();
        // $data = fractal()->item($product)->transformWith(new ProductTransformer)->toJson();

        return view($request->viewPath.'.product', compact('product', 'data'));
    }

    public function category(Category $category, Request $request)
    {
        $data = fractal()->item($category)->transformWith(new CategoryTransformer)->toJson();

        return view($request->viewPath.'.category-products', compact('category', 'data'));
    }

    public function brand(Brand $brand, Request $request)
    {
        $data = fractal()->item($brand)->transformWith(new BrandTransformer)->toJson();

        return view($request->viewPath.'.brand-products', compact('brand', 'data'));
    }

    public function showStorePasswordForm()
    {
        if(!session('store')->setting->enable_password) {
            return redirect('/');
        }
        return view('offlinepage');
    }

    public function verifyStorePassword(Request $request)
    {
        $this->validate($request, [
            'password' => [
                'required',
                Rule::exists('store_settings')->where(function ($query) use($request) {
                    $query->where('store_id', session('store')->id)->where('password', $request->password);
                })
            ]
        ], [
            'password.exists' => 'Password incorrect, please try again.'
        ]);

        $storefront_digest = cookie('storefront_digest', session('store')->setting->password_hash, 0, '/', getStoreDomain(session('store')), false, false);


        return redirect()->route('stores.home')->cookie($storefront_digest);
    }  

    public function searchIndex(Request $request)
    {
        $products = Product::search($request->q)->where('store_id', session('store')->id)->where('state', 1)->where('active', 1)->where('blocked', 0)->paginate(16);
       
        $data = fractal()
                    ->collection($products->getCollection())
                    ->transformWith(new ProductCollectionTransformer)
                    ->paginateWith(new IlluminatePaginatorAdapter($products))
                    ->toArray();

        $pagination = $data['meta']['pagination'];
        $data = $data['data'];
        
        return view($request->viewPath.'.search', compact('data', 'pagination'));

    }

}
