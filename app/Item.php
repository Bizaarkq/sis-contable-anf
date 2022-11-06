<?php

namespace App;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use Timestamp, SoftDeletes;

    protected $table = 'PARTIDA';
    protected $primaryKey = 'ID_PARTIDA';


    public function parts(){
        return $this->hasMany(Part::class, 'ID_PARTIDA', 'ID_PARTIDA'); //tiene muchos parts
    }
    
}
