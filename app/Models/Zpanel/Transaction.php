<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Filters\Payout\PayoutFilters;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
	protected $table = 'sbadmin_transactions';

	public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new PayoutFilters($request))->add($filters)->filter($builder);
    }
}