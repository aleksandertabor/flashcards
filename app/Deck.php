<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    public function cards()
    {
        return $this->belongsToMany('App\Card');
    }
}
