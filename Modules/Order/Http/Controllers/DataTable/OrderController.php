<?php

namespace Modules\Order\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Filters\Order\OrderFilters;

class OrderController extends DataTableController
{
    protected $allowDeletion = false;

    public function builder() {
        return Order::query();
    }

    protected function getRecords(Request $request)
    { 
        $builder = $this->builder;
        $builder = $this->scopeFilter($this->builder, $request, $this->getFilters());
       
        try {
            $records =  $this->builder->selectRaw('orders.id, CONCAT(orders.store_id, "-", orders.order_id) as order_id,CONCAT(customers.firstname,  " ", customers.lastname) AS customer, CONCAT(currencies.iso_code, " ", FORMAT(orders.total_paid, 2)) AS total, orders.payment_method as payment, order_states.name AS status, order_states.slug AS alias, order_states.color as color, orders.order_source, orders.created_at_tz AS date, orders.reference, IF(orders.total_real_paid>0, 1, 0) as payment_status')->join('customers', 'orders.customer_id', '=', 'customers.id', 'inner')->join('order_states', 'orders.current_state', '=', 'order_states.id')->join('currencies', 'orders.currency_id', '=', 'currencies.id')->where('orders.store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    protected function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new OrderFilters($request))->add($filters)->filter($builder);
    }
}
