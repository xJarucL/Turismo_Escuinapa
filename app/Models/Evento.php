<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';
    protected $primaryKey = 'pk_evento';
    
    protected $fillable = [
        'nom_evento',
        'img_promocional',
        'fecha_hora',
        'descripcion'
    ];
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];
    public function getFormattedDateAttribute()
    {
        return $this->fecha_hora->format('d-m-Y H:i');
    }
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_promocional);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenEvento::class, 'pk_evento');
    }
}
