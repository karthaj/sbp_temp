<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class DashboardMenu extends Model
{

	protected $fillable = ['name', 'alias', 'icon', 'order'];

    public $timestamps = false;

    public function getRouteKeyName()
	{
	    return 'alias';
	}

    public function menu() {
        return $this->belongsTo(DashboardMenu::class, 'parent_id');
    }

    public function submenus() {
        return $this->hasMany(DashboardMenu::class, 'parent_id')->orderBy('order', 'asc');
    } 
}
