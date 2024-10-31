<?php

namespace Modules\EmailTemplate\Entities;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [];

    protected $table = 'email_template_customizations';

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
