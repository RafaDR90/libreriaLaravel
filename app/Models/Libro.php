<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo',
        'autor',
        'descripcion',
        'lanzamiento',
        'categoria',
        'prestado',
        'estado',
        'eliminado'
    ];

    protected $attributes = [
        'prestado' => 'no',
        'estado' => 'nuevo',
        'eliminado' => false
    ];
}
