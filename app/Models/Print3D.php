<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Print3D extends Model
{
    protected $table = 'prints';

    protected $fillable = [
        'user_id',
        'printer_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'status',
        'file_name',
        'file_path',
        'notes',
        'material_type_id',
        'material_amount',
        'material_unit',
        'material_source',
    ];

    public function user()
    {
        return $this->belongsTo(PeminjamUser::class, 'user_id');
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

    public function printer()
{
    return $this->belongsTo(Printer::class, 'printer_id');
}

}
