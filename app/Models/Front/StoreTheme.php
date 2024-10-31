<?php

namespace Shopbox\Models\Front;

use Illuminate\Database\Eloquent\Model;

class StoreTheme extends Model
{
   protected $fillable = ['active', 'version'];

   public function theme() {
        return $this->belongsTo('Shopbox\Models\Front\Theme');
   }

   public function store() {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
   }
}
