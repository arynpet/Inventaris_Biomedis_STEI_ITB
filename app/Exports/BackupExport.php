<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Item;
use App\Models\Room;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\ItemOutLog;
use App\Models\Borrowing;
use App\Models\RoomBorrowing;
use App\Models\Print3D;
use App\Models\Printer;
use App\Models\MaterialType;
use App\Models\PeminjamUser;
use App\Models\Category;

class BackupExport implements WithMultipleSheets
{
    protected $modules;
    protected $startDate;
    protected $endDate;

    public function __construct($modules = [], $startDate = null, $endDate = null)
    {
        $this->modules = $modules;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->modules as $module) {
            switch ($module) {
                case 'items':
                    // Items usually don't need date filtering for backup unless specified, keeping all for now or adding created_at filter?
                    // Request says: "User bisa memilih ... Rentang Waktu Tertentu". "Semua Waktu" or range.
                    // Assuming items are master data and should probably be ALL, but let's apply filter if user wants 'created' in that range?
                    // Re-reading: "Tujuan: ... mem-backup/menghapus data ... Rentang Waktu Tertentu".
                    // Usually Master Data (Items, Rooms, Users, Categories, Printers, Materials) are kept FULL.
                    // Transaction Data (Logs, Borrowings, Prints) are filtered.
                    $data = Item::with('room')->get()->map(function ($item) {
                        return [
                            'ID' => $item->id,
                            'Asset No' => $item->asset_number,
                            'SN' => $item->serial_number,
                            'Name' => $item->name,
                            'Room' => $item->room->name ?? '-',
                            'Condition' => $item->condition,
                            'Status' => $item->status,
                            'Quantity' => $item->quantity,
                            'Year' => $item->acquisition_year
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Asset No', 'SN', 'Name', 'Room', 'Condition', 'Status', 'Quantity', 'Year'], 'Barang');
                    break;

                case 'rooms':
                    $data = Room::all()->map(function ($room) {
                        return [
                            'ID' => $room->id,
                            'Name' => $room->name,
                            'Code' => $room->code,
                            'Status' => $room->status
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Nama Ruangan', 'Kode', 'Status'], 'Ruangan');
                    break;

                case 'activity_logs':
                    $query = ActivityLog::with('user')->latest();
                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }
                    $data = $query->take(2000)->get()->map(function ($log) {
                        return [
                            'ID' => $log->id,
                            'Time' => $log->created_at->format('Y-m-d H:i:s'),
                            'Actor' => $log->user->name ?? 'System',
                            'Action' => $log->action,
                            'Subject' => $log->model . ' #' . $log->model_id,
                            'Desc' => strip_tags($log->description)
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Waktu', 'Aktor', 'Aksi', 'Subjek', 'Deskripsi'], 'Log Aktivitas');
                    break;

                case 'users':
                    $data = User::all()->map(function ($u) {
                        return [
                            'ID' => $u->id,
                            'Name' => $u->name,
                            'Email' => $u->email,
                            'Role' => $u->role,
                            'Joined' => $u->created_at->format('Y-m-d')
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Nama', 'Email', 'Role', 'Bergabung'], 'Users');
                    break;

                case 'item_out_logs':
                    $query = ItemOutLog::with('item');
                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }
                    $data = $query->get()->map(function ($out) {
                        return [
                            'ID' => $out->id,
                            'Item' => $out->item->name ?? '-',
                            'Recipient' => $out->recipient_name,
                            'Date' => $out->out_date ? $out->out_date->format('Y-m-d') : '-',
                            'Reason' => $out->reason,
                            'Ref' => $out->reference_number
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Barang', 'Penerima', 'Tanggal Keluar', 'Alasan', 'No Referensi'], 'Barang Keluar');
                    break;

                case 'borrowings':
                    $query = Borrowing::with(['item', 'borrower']);
                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }
                    $data = $query->get()->map(function ($b) {
                        return [
                            'ID' => $b->id,
                            'Item' => $b->item->name ?? '-',
                            'Peminjam' => $b->borrower->name ?? '-',
                            'Tgl Pinjam' => $b->borrow_date ? $b->borrow_date->format('Y-m-d') : '-',
                            'Tgl Kembali' => $b->return_date ? $b->return_date->format('Y-m-d') : '-',
                            'Kondisi Balik' => $b->return_condition,
                            'Status' => $b->status,
                            'Notes' => $b->notes
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Barang', 'Peminjam', 'Tgl Pinjam', 'Tgl Kembali', 'Kondisi Balik', 'Status', 'Notes'], 'Peminjaman Barang');
                    break;

                case 'room_borrowings':
                    $query = RoomBorrowing::with(['room', 'user']);
                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }
                    $data = $query->get()->map(function ($rb) {
                        return [
                            'ID' => $rb->id,
                            'Ruangan' => $rb->room->name ?? '-',
                            'Peminjam' => $rb->user->name ?? '-',
                            'Mulai' => $rb->start_time ? $rb->start_time->format('Y-m-d H:i') : '-',
                            'Selesai' => $rb->end_time ? $rb->end_time->format('Y-m-d H:i') : '-',
                            'Tujuan' => $rb->purpose,
                            'Status' => $rb->status
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Ruangan', 'Peminjam', 'Mulai', 'Selesai', 'Tujuan', 'Status'], 'Booking Ruangan');
                    break;

                case 'prints':
                    $query = Print3D::with(['user', 'printer', 'materialType']);
                    if ($this->startDate && $this->endDate) {
                        $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }
                    $data = $query->get()->map(function ($p) {
                        return [
                            'ID' => $p->id,
                            'User' => $p->user->name ?? '-',
                            'Project' => $p->project_name,
                            'Printer' => $p->printer->name ?? '-',
                            'Material' => $p->materialType->name ?? '-',
                            'Gram' => $p->material_amount,
                            'Status' => $p->status,
                            'Date' => $p->date
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'User', 'Project', 'Printer', 'Material', 'Gram', 'Status', 'Date'], '3D Printing');
                    break;

                case 'printers':
                    $data = Printer::all()->map(function ($p) {
                        return [
                            'ID' => $p->id,
                            'Name' => $p->name,
                            'Brand' => $p->brand,
                            'Status' => $p->status
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Nama Printer', 'Merk', 'Status'], 'Data Printer');
                    break;

                case 'materials':
                    $data = MaterialType::all()->map(function ($m) {
                        return [
                            'ID' => $m->id,
                            'Type' => $m->name,
                            'Color' => $m->color,
                            'Stock (g)' => $m->stock_grams,
                            'Brand' => $m->brand
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Jenis', 'Warna', 'Stok (gram)', 'Merk'], 'Stok Material');
                    break;

                case 'borrowers':
                    $data = PeminjamUser::all()->map(function ($bu) {
                        return [
                            'ID' => $bu->id,
                            'Name' => $bu->name,
                            'NIM/NIP' => $bu->nim,
                            'Phone' => $bu->phone,
                            'Email' => $bu->email,
                            'Institution' => $bu->institution
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Nama', 'NIM/NIP', 'No HP', 'Email', 'Institusi'], 'Data Peminjam');
                    break;

                case 'categories':
                    $data = Category::withCount('items')->get()->map(function ($c) {
                        return [
                            'ID' => $c->id,
                            'Name' => $c->name,
                            'Total Items' => $c->items_count
                        ];
                    });
                    $sheets[] = new GenericSheet($data, ['ID', 'Kategori', 'Jumlah Item'], 'Kategori');
                    break;
            }
        }

        return $sheets;
    }
}
