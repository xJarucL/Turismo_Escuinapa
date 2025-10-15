<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComidaTipica extends Model
{
    use HasFactory;

    protected $primaryKey = 'pk_comida_tipica';
    protected $table = 'comida_tipica';

    protected $fillable = [
        'nom_comida',
        'descripcion',
        'ingredientes',
        'img_comida',
        'fk_cat_comida',
        'estatus',
    ];

    public function cat_comida(){
        return $this->belongsTo(CategoriaComida::class, 'fk_cat_comida', 'pk_cat_comida');
    }

    public $timestamps = true;

    protected $casts = [
        'estatus' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_comida);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenComidaTipica::class, 'pk_comida_tipica');
    }
}
