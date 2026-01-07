<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity; // <--- Import Trait

class Category extends Model
{
        use LogsActivity; // <--- Pasang CCTV disini
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item');
    }
}
