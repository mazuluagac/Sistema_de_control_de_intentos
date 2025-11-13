<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estudios',
    ];

    protected $visible = [
        'nombre',
        'estudios',
    ];

    public $timestamps = false;
}
