<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenLugaresInteres extends Model
{
    use HasFactory;

    protected $table = 'imagenes_lugares_interes';

    protected $primaryKey = 'pk_img_lugar_interes';

    protected $fillable = [
        'pk_lugar_interes',
        'ruta',
    ];

    public $timestamps = true;


    public function lugarInteres(){
        return $this->belongsTo(LugaresInteres::class, 'pk_lugar_interes', 'pk_lugar_interes');
    }
}
