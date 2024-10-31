<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';

    protected $fillable = ['secret', 'redirect'];

    protected $primaryKey = 'id';

    public $incrementing = false;

  
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];

    public function store()
    {
    	return $this->belongsTo(Store::class, 'id');
    }

}
