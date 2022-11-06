<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{   
    protected $table = 'LIBRO_DIARIO';
    protected $primaryKey = 'ID_LIBRO_DIARIO';
    //protected $dateFormat = 'd-m-Y';
    //public $timestamps = false;

    public function accounts(){
        return $this->belongsTo(Account::class, 'ID_CATALOGO', 'ID_CATALOGO'); //tiene muchos accounts
    }

    public function items(){
        return $this->belongsTo(Item::class, 'ID_PARTIDA', 'ID_PARTIDA'); //pertenece a part
    }


}
