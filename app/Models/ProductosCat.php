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

    public function producto()
    {
        return $this->belongsTo(productos::class, 'producto_id');
    }

    public function categoria()
    {
        return $this->belongsTo(categorias::class, 'categoria_id');
    }
}
