<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosCat extends Model
{
    protected $table = 'producto_categorias';
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'categoria_id'
    ];
}
