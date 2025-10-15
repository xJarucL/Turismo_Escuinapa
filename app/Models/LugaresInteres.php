<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LugaresInteres extends Model
{
    use HasFactory;

    protected $table = 'lugares_interes';
    protected $primaryKey = 'pk_lugar_interes';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'url_google_resena',
        'img_portada',
        'estatus'
    ];

    public $timestamps = true;

    protected $casts = [
        'estatus' => 'boolean',
    ];

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_portada);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenLugaresInteres::class, 'pk_lugar_interes');
    }
}
