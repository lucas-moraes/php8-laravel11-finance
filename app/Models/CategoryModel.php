<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'categoria';
    public $timestamps = false;
    protected $fillable = [
        'idCategory',
        'descricao',
    ];
}
