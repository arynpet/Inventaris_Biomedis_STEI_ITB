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
        $this->actingAs(User::factory()->create());
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
        Storage::fake('public');

        $user = PeminjamUser::factory()->create(['is_trained' => true]);
        $printer = Printer::factory()->create();
        $material = MaterialType::factory()->create(['stock_balance' => 1000]);

        $printData = [
            'user_id' => $user->id,
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
        $user = PeminjamUser::factory()->create(['is_trained' => true]);
        $printer = Printer::factory()->create();

        $printData = [
            'user_id' => $user->id,
            'printer_id' => $printer->id,
            'date' => now()->format('Y-m-d'), // Should be at least 2 days from now
            'start_time' => '09:00',
            'end_time' => '12:00',
        ];

        $response = $this->post(route('prints.store'), $printData);

        $response->assertSessionHasErrors('date');
    }

    #[Test]
    public function it_deducts_material_when_printing_starts()
    {
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_deducted' => false,
        ]);
        
        $material = MaterialType::factory()->create([
            'id' => $print->material_type_id,
            'stock_balance' => 1000,
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

        $this->assertDatabaseHas('material_types', [
            'id' => $material->id,
            'stock_balance' => 1000 - $print->material_amount,
        ]);
    }

    #[Test]
    public function it_refunds_material_when_canceled()
    {
        $print = Print3D::factory()->create([
            'status' => 'pending',
            'material_deducted' => true,
            'material_amount' => 50,
        ]);

        $material = MaterialType::factory()->create([
            'id' => $print->material_type_id,
            'stock_balance' => 500,
        ]);

        $response = $this->put(route('prints.update', $print), [
            'status' => 'canceled',
        ]);

        $this->assertDatabaseHas('material_types', [
            'id' => $material->id,
            'stock_balance' => 550, // 500 + 50
        ]);

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
            'printer_id' => $printer->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'file_upload' => $file,
        ];

        $response = $this->post(route('prints.store'), $printData);

        $response->assertRedirect(route('prints.index'));

        Storage::disk('public')->assertExists('prints/' . $file->hashName());
    }
}