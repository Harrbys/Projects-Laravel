<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos_check extends Model
{
    protected $table = 'productos_checks';
    use HasFactory;

    protected $fillable = [
        'codigo',
        'productos',
        'cliente_id'
    ];

    protected $casts = [
        'productos' => 'array',
    ];


    public function productos()
    {
        return $this->belongsToMany(productos::class, 'producto_producto_check', 'producto_check_id', 'producto_id');
    }
}


