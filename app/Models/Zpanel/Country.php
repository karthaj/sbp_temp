<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function shippingZones() 
    {
        return $this->hasMany('Modules\Product\Entities\ShippingZoneLocation');
    }

    public function taxRule() 
    {
        return $this->hasOne('Modules\Product\Entities\TaxRule');
    }

    public function discounts ()
    {
        return $this->belongsToMany('Modules\Discount\Entities\Discount');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Replace letters of zip code format And check this format on the zip code
     *
     * @param string $zipCode zip code
     *
     * @return bool Indicates whether the zip code is correct
     */
    public function checkZipCode($country_id, $zipCode)
    {
        $country = Country::find($country_id);
        if (empty($country->zip_code_format)) {
            return true;
        }

        $zipRegexp = '/^'.$country->zip_code_format.'$/ui';
        $zipRegexp = str_replace(' ', '( |)', $zipRegexp);
        $zipRegexp = str_replace('-', '(-|)', $zipRegexp);
        $zipRegexp = str_replace('N', '[0-9]', $zipRegexp);
        $zipRegexp = str_replace('L', '[a-zA-Z]', $zipRegexp);
        $zipRegexp = str_replace('C', $this->iso_code, $zipRegexp);

        return (bool) preg_match($zipRegexp, $zipCode);
    }
}
