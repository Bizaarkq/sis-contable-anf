<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{   
    protected $table = 'CATALOGO';
    protected $primaryKey = 'ID_CATALOGO';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function parts(){
        return $this->belongsTo(Part::class); //pertenece a part
    }
}
