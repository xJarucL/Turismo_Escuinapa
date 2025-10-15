<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotel';
    protected $primaryKey = 'pk_hotel';
    

    protected $fillable = [
        'nom_hotel',
        'img_hotel',
        'direccion',
        'contacto',
        'descripcion',
        'link_hotel',
        'estatus'
    ];

    public $timestamps = true;

    protected $casts = [
        'estatus' => 'boolean',
    ];  

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_hotel);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenHotel::class, 'pk_hotel');
    }


}
