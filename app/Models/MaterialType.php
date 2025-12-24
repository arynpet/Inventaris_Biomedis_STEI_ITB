<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialType extends Model
{
    protected $table = 'material_types';

    use HasFactory;
    
    protected $fillable = [
        'category',
        'name',
        'stock_balance',
        'unit',
    ];
}
