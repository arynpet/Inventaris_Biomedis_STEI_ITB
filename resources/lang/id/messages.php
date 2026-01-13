<?php

return [
    // Room Borrowing - Error messages kritis
    'room' => [
        'overlap' => 'Ruangan sudah dipesan di waktu tersebut! Silakan pilih waktu lain.',
        'overlap_update' => 'Jadwal bentrok dengan peminjaman lain!',
    ],
    
    // Print 3D - Error messages kritis
    'print' => [
        'user_not_trained' => 'User ini belum mengikuti pelatihan (Training)!',
        'schedule_overlap' => 'Waktu print bentrok dengan jadwal lain di mesin ini!',
        'invalid_status_transition' => 'Status :old_status tidak dapat diubah ke :new_status',
    ],
    
    // Borrowing - Error messages kritis
    'borrowing' => [
        'already_returned' => 'Barang sudah dikembalikan sebelumnya.',
        'item_not_available' => 'Item sedang dipinjam atau dalam perbaikan.',
    ],
    
    // Bulk actions
    'bulk' => [
        'no_selection' => 'Tidak ada item yang dipilih.',
        'invalid_action' => 'Aksi tidak valid.',
    ],
];
