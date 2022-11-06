<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Timestamp;

    protected $table = 'PARTIDA';
    protected $primaryKey = 'ID_PARTIDA';


    public function parts(){
        return $this->hasMany(Part::class); //tiene muchos parts
    }
    
}
