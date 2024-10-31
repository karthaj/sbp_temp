<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'order', 'url', 'type'];

    public function menu()
    {
    	return $this->belongsTo(Menu::class);
    }

    public function children() {
        return $this->hasMany(Item::class, 'parent_id')->orderBy('order', 'asc');
    }
    
    public function item() {
        return $this->belongsTo(Item::class, 'parent_id');
    }
}
