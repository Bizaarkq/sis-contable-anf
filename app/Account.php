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
        return $this->hasMany(Part::class, 'ID_CATALOGO','ID_CATALOGO'); //pertenece a part
    }

    public function subaccounts(){
        return $this->hasMany(Account::class, 'CUENTA_PADRE', 'CODIGO_CATALOGO'); //pertenece a subaccount
    }

    public function allSubaccounts(){
        return $this->subaccounts()->with('allSubaccounts');
    }

    public function parent(){
        return $this->belongsTo(Account::class, 'CUENTA_PADRE', 'CODIGO_CATALOGO'); //tiene un padre
    }

    public function allParents(){
        return $this->parent()->with('allParents');
    }

}
