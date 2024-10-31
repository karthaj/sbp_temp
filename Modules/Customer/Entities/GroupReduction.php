<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;

class GroupReduction extends Model
{
    protected $fillable = ['discount'];

    public function group ()
    {
        return $this->belongsTo(Group::class);
    }

    public function category ()
    {
        return $this->belongsTo('Modules\Product\Entities\Category');
    }

    public function store ()
    {
        return $this->belongsTo(Group::class);
    }

}
