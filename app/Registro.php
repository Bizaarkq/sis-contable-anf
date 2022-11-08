<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Registro extends Model
{
    use SoftDeletes;
    protected $table = 'REGISTRO';
    protected $primaryKey = 'ID_REGISTRO';

    protected $attributes = [
        "CREATED_USER" => 'system',
        "UPDATED_USER" => 'system',
    ];
}
