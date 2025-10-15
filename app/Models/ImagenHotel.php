<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagenHotel extends Model
{
    use HasFactory;

    protected $table = 'imagenes_hoteles';

    protected $primaryKey = 'pk_img_hotel';

    protected $fillable = ['pk_hotel', 'ruta'];

    public $timestamps = true;  

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'pk_hotel', 'pk_hotel');
    }
}
