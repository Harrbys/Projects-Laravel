<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'full_name',
        'telefono',
        'cargo',
        'area',
        'jefe'

    ];

    // segun laravek esta funcion con el metodo belongsTo es para asigna un jefe para la tabla recursiva pero no si si eso ya no seria necesario para ek wherebelongTo 

    public function jefe()
    {
        return $this->belongsTo(Empleados::class, 'jefe');
    }

    // crear un empleado con un jefe cargo utilizando el HasMany
    public function subordinados()
    {
        return $this->hasMany(Empleados::class, 'jefe');
    }
}
