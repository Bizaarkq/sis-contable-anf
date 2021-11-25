<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{   
    //protected $dateFormat = 'd-m-Y';
    public $timestamps = false;

    public function accounts(){
        return $this->hasMany(Account::class); //tiene muchos accounts
    }

    public function items(){
        return $this->belongsTo(Item::class); //pertenece a part
    }


}
