<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagenComidaTipica extends Model
{
    use HasFactory;

    protected $table = 'imagenes_comida_tipica';

    protected $primaryKey = 'pk_img_comida_tipica';

    protected $fillable = [
        'pk_comida_tipica',
        'ruta',
    ];

    public $timestamps = true;


    public function comidaTipica(){
        return $this->belongsTo(ComidaTipica::class, 'pk_comida_tipica', 'pk_comida_tipica');
    }
}
