<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_number',
        'room_id',
        'quantity',
        'source',
        'acquisition_year',
        'placed_in_service_at',
        'fiscal_group',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
}
