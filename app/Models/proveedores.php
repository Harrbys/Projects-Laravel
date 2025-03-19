<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proveedores extends Model
{
    protected $table = 'proveedores';
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'cedula', 
        'direccion', 
        'telefono',
        'empresa_id'
    ];

    public function productos()
    {
        return $this->hasMany(productos::class, 'proveedores_id');
    }
}
