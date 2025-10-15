<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presidente extends Model
{
     use HasFactory;

    protected $table = 'presidente';

    protected $primaryKey = 'pk_presidente';
    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'fec_inicio',
        'fec_fin',
        'descripcion',
        'img_presidente',
        'estatus',
    ];

    protected $casts = [
        'fec_inicio' => 'date',
        'fec_fin' => 'date',
        'estatus' => 'boolean',
    ];

    public function getImagenUrlAttribute(){
        return Storage::url($this->img_presidente);
    }
}
