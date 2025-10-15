<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenEvento extends Model
{
    use HasFactory;

    protected $table = 'imagenes_eventos';

    protected $primaryKey = 'pk_img_evento';

    protected $fillable = ['pk_evento', 'ruta'];

    public $timestamps = true;

    
    public function evento(){
        return $this->belongsTo(Evento::class, 'pk_evento', 'pk_evento');
}
}
