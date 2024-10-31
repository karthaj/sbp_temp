<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class PluginPlan extends Model
{
    public function plugins()
    {
        return $this->belongsTo(Plugin::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
