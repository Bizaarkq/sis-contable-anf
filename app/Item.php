<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    public function parts(){
        return $this->hasMany(Part::class); //tiene muchos parts
    }
    
}
