<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EventoAnual extends Model
{
    use HasFactory;

    protected $table = 'evento_anual';
    protected $primaryKey = 'pk_evento_anual';

    protected $fillable = [
        'nom_evento',
        'img_promocional',
        'fecha_referencia',
        'descripcion',
        'direccion',
        'hora_evento',
        'estatus'
    ];

    public function getFormattedDateAttribute()
    {
    if ($this->tipo_fecha === 'fija' || $this->tipo_fecha === 'dia_mes') {
        return Carbon::createFromFormat('m-d', $this->fecha_referencia)->format('d M');
    }

    // Para fechas variables solo retornamos el texto
    return $this->fecha_referencia;
    }


    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->img_promocional);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenEventoAnual::class, 'pk_evento_anual');
    }
}
