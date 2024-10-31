<?php

namespace Modules\EmailTemplate\Entities;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['email_template_name','slug','email_subject','email_body','created_by','updated_by','browser','platform','ip_address'];

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
