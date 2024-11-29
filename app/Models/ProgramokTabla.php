<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramokTabla extends Model
{
    use HasFactory;

    protected $table = 'programok_tabla';
    public $timestamps = false;
}
