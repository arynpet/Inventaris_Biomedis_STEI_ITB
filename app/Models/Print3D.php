<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity; // <--- Import Trait

class Print3D extends Model
{
    use LogsActivity; // <--- Pasang CCTV disini
    protected $table = 'prints';

    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name', // <--- TAMBAHKAN INI
        'printer_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'file_name',
        'file_name',
        'file_path',
        'stl_path', // ⬅ NEW
        'notes',
        'material_type_id',
        'material_amount',
        'material_unit',
        'material_source',
        'lecturer_name',
        'material_deducted', // ⬅ WAJIB
    ];

    protected $casts = [
        'material_deducted' => 'boolean',
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

    public function getDurationAttribute()
    {
        // Pastikan start_time dan end_time ada datanya
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->diffInMinutes($end);
    }
}
