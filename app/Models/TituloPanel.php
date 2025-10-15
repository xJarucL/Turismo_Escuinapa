<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TituloPanel extends Model
{
    use HasFactory;

    protected $table = 'titulo_panel';
    protected $primaryKey = 'pk_titulo_panel';

    protected $fillable = [
        'titulo',
        'subtitulo',
    ];
}
