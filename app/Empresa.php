<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Empresa extends Model
{
    use SoftDeletes;

    protected $table = 'EMPRESA';
    protected $primaryKey = 'ID_EMPRESA';

    public function users(){
        return $this->belongsToMany(User::class, 'ACCESO_USUARIO', 'ID_EMPRESA', 'ID_USUARIO');
    }

}
