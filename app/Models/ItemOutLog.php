<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; // <--- Import Trait

class ItemOutLog extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama class singular + tabel plural, tapi bagus untuk eksplisit)
    protected $table = 'item_out_logs';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'item_id',
        'recipient_name',
        'out_date',
        'reason',
        'reference_file',   // Untuk path file upload
        'reference_number', // Jika nanti masih butuh nomor surat manual
    ];

    // Casting agar 'out_date' dibaca sebagai objek Carbon (memudahkan format tanggal di View)
    protected $casts = [
        'out_date' => 'date',
    ];

    /**
     * Relasi ke model Item
     * Log pengeluaran ini milik satu Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}