<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\Report\ReportFilters;

class StoreVisit extends Model
{
	public $timestamps = false;
	protected $dates = ['created_at'];

    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new ReportFilters($request))->add($filters)->filter($builder);
    }

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function country()
    {
    	return $this->belongsTo(Country::class);
    }
}
