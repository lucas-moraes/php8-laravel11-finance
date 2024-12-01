<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovementModel extends Model
{
    protected $table = 'lc_movimento';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'dia',
        'mes',
        'ano',
        'tipo',
        'categoria',
        'descricao',
        'valor'
    ];
}
