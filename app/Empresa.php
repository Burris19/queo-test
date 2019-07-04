<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'name',
        'email',
        'logo',
        'website'
    ];

    public function empleados()
    {
        return $this->hasMany('App\Empleado');
    }
}
