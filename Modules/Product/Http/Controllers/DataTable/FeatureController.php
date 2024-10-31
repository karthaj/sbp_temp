<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Feature;

class FeatureController extends DataTableController
{
    public function builder() {
        return Feature::query()->orderBy('sort_order', 'asc');
    }

    public function getDisplayableColumns()
    {
        return [
            'id', 'name', 'sort_order'
        ];
    }
}
