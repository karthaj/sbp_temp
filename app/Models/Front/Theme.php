<?php

namespace Shopbox\Models\Front;

use Shopbox\Filters\Theme\ThemeFilters;
use Illuminate\Database\Eloquent\Builder;   
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['theme_name', 'price', 'featured', 'status'];

    public function getRouteKeyName()
    {
        return 'alias';
    }

    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new ThemeFilters($request))->add($filters)->filter($builder);
    }

    public function industries()
    {
    	return $this->belongsToMany(Industry::class, 'theme_industries')->withPivot('variation', 'color', 'desktop_screenshot', 'tab_screenshot', 'mobile_screenshot');
    }

    public function getFormattedPriceAttribute()
    {
        return 'LKR '.number_format($this->price, 2);
    }

}
