<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $table = 'presentations';
    protected $softDelete = true;


    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
