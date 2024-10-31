<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{

    protected $fillable = ['meta_title', 'meta_keywords', 'meta_description', 'password_hash', 'password', 'message', 'enable_password', 'order_id_prefix', 'order_id_suffix', 'google_tag_manager', 'google_analytics', 'facebook_pixel_id', 'captcha_site_key', 'captcha_secret_key', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $primaryKey = 'store_id';

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'store_currency');
    }

    public function weight()
    {
        return $this->belongsTo(WeightUnit::class, 'weight_unit_id');
    }
}
