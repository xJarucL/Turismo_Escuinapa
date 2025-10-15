<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenRestaurante extends Model
{
    protected $table = 'imagenes_restaurantes';

    protected $primaryKey = 'pk_img_restaurante';

    protected $fillable = ['pk_restaurante', 'ruta'];

    public $timestamps = true;  

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'pk_restaurante', 'pk_restaurante');
    }

}
