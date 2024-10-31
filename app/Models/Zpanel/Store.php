<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Models\Zpanel\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Store extends Model
{  
    use Notifiable;

    protected $fillable = ['store_name', 'domain', 'store_url', 'main', 'account_email', 'customer_email', 'store_address', 'active', 'expiry_date', 'grace_period', 'suspended'];

    protected $dates = ['expiry_date'];

    public $incrementing  = false;

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->store_email;
    }

    public function hasBillingAddress()
    {
        if(!empty($this->company) && !empty($this->address1) && !empty($this->city) && !empty($this->country) && !empty($this->postcode)) {
            return true;
        }
        return false;
    }

    public function visits()
    {
        return $this->hasMany(StoreVisit::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function permissions () 
    {
        return $this->belongsToMany(Permission::class, 'stores_permissions')->withTimestamps();
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id');
    }

    public function plugins()
    {
        return $this->hasMany(StorePlugin::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users')->withTimestamps();
    }

    public function customers() 
    {
        return $this->belongsToMany('Modules\Customer\Entities\Customer', 'store_customers')->withTimestamps();
    }

    public function discounts() 
    {
        return $this->hasMany('Modules\Discount\Entities\Discount');
    }

    public function stocks() 
    {
        return $this->hasMany('Modules\Product\Entities\Stock');
    }

    public function roles() 
    {
        return $this->hasMany(Role::class);
    }

    public function setting()
    {
        return $this->hasOne(StoreSetting::class);
    }
    
    public function categories() 
    {
        return $this->hasMany('Modules\Product\Entities\Category');
    }

    public function brands() 
    {
        return $this->hasMany('Modules\Product\Entities\Brand');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function weight()
    {
        return $this->belongsTo(WeightUnit::class);
    }

    public function products() 
    {
        return $this->hasMany('Modules\Product\Entities\Product')->where('state', 1);
    }

    public function productImages() 
    {
        return $this->hasMany('Modules\Product\Entities\ProductImage');
    }

    public function features() 
    {
        return $this->hasMany('Modules\Product\Entities\Feature');
    }

    public function taxOption()
    {
        return $this->hasOne('Modules\Product\Entities\TaxOption');
    }

    public function taxClasses() 
    {
        return $this->hasMany('Modules\Product\Entities\TaxClass');
    }

    public function taxZones() 
    {
        return $this->hasMany('Modules\Product\Entities\TaxZone');
    }

    public function taxRules() 
    {
        return $this->hasMany('Modules\Product\Entities\TaxRule');
    }

    public function tax() 
    {
        return $this->hasMany('Modules\Product\Entities\Tax');
    }

    public function taxRates() 
    {
        return $this->hasMany('Modules\Product\Entities\TaxRate');
    }

    public function storeLocations() 
    {
        return $this->hasMany('Modules\Product\Entities\StoreLocation');
    }

    public function onlineStore() 
    {
        return $this->hasOne('Modules\Product\Entities\StoreLocation')->where('online_sales', 1);
    }

    public function menus() 
    {
        return $this->hasMany('Modules\Menu\Entities\Menu');
    }

    public function shippingZones() 
    {
        return $this->hasMany('Modules\Product\Entities\ShippingZone')->orderBy('created_at', 'desc');
    }

    public function shippingClasses() 
    {
        return $this->hasMany('Modules\Product\Entities\ShippingClass')->orderBy('created_at', 'desc');
    }

    public function carts() 
    {
        return $this->hasMany('Modules\Product\Entities\Cart');
    }

    public function pages() 
    {
        return $this->hasMany('Modules\Page\Entities\Page');
    }

    public function blogs() 
    {
        return $this->hasMany('Modules\Blog\Entities\Blog')->where('blocked', 0);
    }

    public function configurations() 
    {
        return $this->hasMany(Configuration::class);
    }

    public function themes()
    {
        return $this->hasMany('Shopbox\Models\Front\StoreTheme');
    }

    public function template()
    {
        return $this->hasone('Shopbox\Models\Front\StoreTheme')->where('active', 1);
    }

    public function cash_on_deliveries()
    {
        return $this->hasMany('Modules\CashOnDelivery\Entities\COD');
    }

    public function payments()
    {
        return $this->hasMany(StorePayment::class);
    }

    public function orders()
    {
        return $this->hasMany('Modules\Order\Entities\Order');
    }

    public function returns()
    {
        return $this->hasMany('Modules\Order\Entities\OrderReturn')->orderBy('id', 'desc');
    }

    public function emails()
    {
        return $this->hasMany('Modules\EmailTemplate\Entities\Email');
    }

    public function email_template_customization()
    {
        return $this->hasone('Modules\EmailTemplate\Entities\Template');
    }

    public function hasPermissionTo($permission) 
    {

        return $this->hasPermissionThroughPlan($permission) || $this->hasPermission($permission);

    }

    public function credits()
    {
        return $this->hasMany(StoreCredit::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function billing_invoices()
    {
        return $this->hasMany(BillingInvoice::class);
    }

    public function wishlists()
    {
        return $this->hasMany('Shopbox\Models\Wishlist');
    }

    protected function hasPermission ($permission) {
    
        return (bool) $this->permissions->where('name', $permission->name)->count();

    }
    
    protected function hasPermissionThroughPlan ($permission) { 

        foreach($permission->plans as $plan) {
            if ($this->plan->id === $plan->id) {
                return true;    
            }
        }
        return false;   

    }


}
