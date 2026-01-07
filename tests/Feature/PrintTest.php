<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Print3D;
use App\Models\Printer;
use App\Models\PeminjamUser;
use App\Models\MaterialType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class PrintTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);
    }

    #[Test]
    public function it_can_display_prints_index()
    {
        $response = $this->get(route('prints.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('prints.index');
    }

    #[Test]
    public function it_can_create_print_job()
    {
        $user = PeminjamUser::factory()->trained()->create();
        $printer = Printer::factory()->create();
        $material = MaterialType::factory()->create(['stock_balance' => 1000]);

        $printData = [
            'user_id' => $user->id,
            'project_name' => 'Test Project',
            'printer_id' => $printer->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'material_type_id' => $material->id,
            'material_amount' => 50,
            'material_unit' => 'gram',
            'material_source' => 'lab',
            'notes' => 'Test print job',
        ];

        $response = $this->post(route('prints.store'), $printData);

        $response->assertRedirect(route('prints.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('prints', [
            'user_id' => $user->id,
            'printer_id' => $printer->id,
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function it_validates_minimum_date_for_print()
    {
        $user = PeminjamUser::factory()->trained()->create();
        $printer = Printer::factory()->create();

        $printData = [
            'user_id' => $user->id,
            'project_name' => 'Test Minimum Date',
            'printer_id' => $printer->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
        ];

        $response = $this->post(route('prints.store'), $printData);

        $response->assertSessionHasErrors('date');
    }

    #[Test]
    public function it_deducts_material_when_printing_starts()
    {
        $material = MaterialType::factory()->create(['stock_balance' => 1000]);
        
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_deducted' => false,
            'material_type_id' => $material->id,
            'material_amount' => 100,
        ]);

        $response = $this->put(route('prints.update', $print), [
            'status' => 'printing',
        ]);

        $response->assertRedirect(route('prints.index'));

        $this->assertDatabaseHas('prints', [
            'id' => $print->id,
            'status' => 'printing',
            'material_deducted' => true,
        ]);

        $material->refresh();
        $this->assertEquals(900, $material->stock_balance);
    }

    #[Test]
    public function it_refunds_material_when_canceled()
    {
        $material = MaterialType::factory()->create(['stock_balance' => 500]);
        
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_deducted' => true,
            'material_type_id' => $material->id,
            'material_amount' => 50,
        ]);

        $response = $this->put(route('prints.update', $print), [
            'status' => 'canceled',
        ]);

        $material->refresh();
        $this->assertEquals(550, $material->stock_balance);

        $this->assertDatabaseHas('prints', [
            'id' => $print->id,
            'material_deducted' => false,
        ]);
    }

    #[Test]
    public function untrained_user_cannot_create_print_job()
    {
        $user = PeminjamUser::factory()->create(['is_trained' => false]);
        $printer = Printer::factory()->create();

        $printData = [
            'user_id' => $user->id,
            'project_name' => 'Untrained Test',
            'printer_id' => $printer->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
        ];

        $response = $this->post(route('prints.store'), $printData);

        $response->assertSessionHasErrors('user_id');
    }

    #[Test]
    public function it_can_upload_file_with_print_job()
    {
        Storage::fake('public');

        $user = PeminjamUser::factory()->create(['is_trained' => true]);
        $printer = Printer::factory()->create();
        $file = UploadedFile::fake()->create('design.pdf', 1000);

        $printData = [
            'user_id' => $user->id,
            'project_name' => 'File Upload Test',
            'printer_id' => $printer->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'file_upload' => $file,
        ];

        $this->post(route('prints.store'), $printData);

        $print = Print3D::where('user_id', $user->id)->first();

        $this->assertNotNull($print->file_path);
        Storage::disk('public')->assertExists($print->file_path);
    }

    #[Test]
    public function it_prevents_invalid_status_transitions()
    {
        // Skenario: Status sudah 'done', mencoba diubah kembali ke 'pending'
        $print = Print3D::factory()->create(['status' => 'done']);

        $response = $this->put(route('prints.update', $print), [
            'status' => 'pending',
        ]);

        // Harapannya: Gagal, kembali ke halaman sebelumnya, ada error di field 'status'
        $response->assertSessionHasErrors('status');
        
        // Pastikan data di database TIDAK berubah
        $this->assertEquals('done', $print->fresh()->status);
    }

    #[Test]
    public function it_prevents_printing_if_stock_is_insufficient()
    {
        // Material sisa 10 gram
        $material = MaterialType::factory()->create(['stock_balance' => 10]);
        
        // Print butuh 50 gram
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_type_id' => $material->id,
            'material_amount' => 50, 
            'material_deducted' => false,
        ]);

        // Coba update ke 'printing'
        $response = $this->put(route('prints.update', $print), [
            'status' => 'printing',
        ]);

        // Harapannya: Error pada key 'stock' (sesuai controller tadi)
        $response->assertSessionHasErrors('stock');

        // Pastikan status masih 'pending' dan stok TIDAK berkurang
        $this->assertEquals('pending', $print->fresh()->status);
        $this->assertEquals(10, $material->fresh()->stock_balance);
    }

    #[Test]
    public function it_deducts_material_when_status_changes_to_printing()
    {
        $material = MaterialType::factory()->create(['stock_balance' => 1000]);
        
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_deducted' => false,
            'material_type_id' => $material->id,
            'material_amount' => 100,
        ]);

        $response = $this->put(route('prints.update', $print), [
            'status' => 'printing',
        ]);

        $response->assertSessionHas('success');

        // Cek DB: Status berubah, flag deducted true
        $this->assertDatabaseHas('prints', [
            'id' => $print->id,
            'status' => 'printing',
            'material_deducted' => true,
        ]);

        // Cek Stok: 1000 - 100 = 900
        $this->assertEquals(900, $material->fresh()->stock_balance);
    }

    #[Test]
    public function it_refunds_material_when_canceled_after_deduction()
    {
        $material = MaterialType::factory()->create(['stock_balance' => 500]);
        
        // Skenario: Sedang printing (stok sudah terpotong)
        $print = Print3D::factory()->create([
            'status' => 'printing',
            'material_deducted' => true, // Stok sudah diambil
            'material_type_id' => $material->id,
            'material_amount' => 50,
        ]);

        // User membatalkan
        $response = $this->put(route('prints.update', $print), [
            'status' => 'canceled',
        ]);

        // Cek DB: Status canceled, flag deducted false
        $this->assertDatabaseHas('prints', [
            'id' => $print->id,
            'status' => 'canceled',
            'material_deducted' => false,
        ]);

        // Cek Stok: 500 + 50 = 550 (Refund berhasil)
        $this->assertEquals(550, $material->fresh()->stock_balance);
    }
}