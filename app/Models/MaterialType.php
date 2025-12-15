<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    protected $table = 'material_types';

    protected $fillable = [
        'category',
        'name',
    ];
}
