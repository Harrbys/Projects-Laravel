<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    protected $table = 'productos';
    use HasFactory;

    protected $fillable = [
        'codigo', 
        'nombre', 
        'descripcion', 
        'precio', 
        'stock',
        'proveedores_id'
    ];

    public function productoChecks()
    {
        return $this->belongsToMany(productos_check::class, 'intermedia_checks');
    }

    public function proveedor()
    {
        return $this->belongsTo(proveedores::class, 'proveedores_id');
    }

}
