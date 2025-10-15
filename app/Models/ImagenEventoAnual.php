<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagenEventoAnual extends Model
{
    use HasFactory;

    protected $table = 'imagenes_eventos_anuales';

    protected $primaryKey = 'pk_img_evento_anual';

    protected $fillable = ['pk_evento_anual', 'ruta'];

    public $timestamps = true;

    
    public function evento(){
        return $this->belongsTo(EventoAnual::class, 'pk_evento_anual', 'pk_evento_anual');

}
}
