<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity; // <--- Import Trait

class MaterialType extends Model
{
        use LogsActivity; // <--- Pasang CCTV disini
    protected $table = 'material_types';

    use HasFactory;
    
    protected $fillable = [
        'category',
        'name',
        'stock_balance',
        'unit',
    ];
}
