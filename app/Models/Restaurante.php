<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Restaurante extends Model
{
    use HasFactory;

    protected $table = 'restaurante';
    protected $primaryKey = 'pk_restaurante';

        protected $fillable = [
        'nom_restaurante',
        'direccion',
        'hora_apertura',
        'hora_cierre',
        'descripcion',
        'img_promocional',
        'email_restaurante',
        'url_google_reseña',
        'estatus'
    ];

    protected $casts = [
        'estatus' => 'boolean',
    ];
    
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_promocional);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenRestaurante::class, 'pk_restaurante');
    }

}
