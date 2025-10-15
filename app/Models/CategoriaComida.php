<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriaComida extends Model
{
    use HasFactory;

    protected $primaryKey = 'pk_cat_comida';
    protected $table = 'categoria_comida';
}
