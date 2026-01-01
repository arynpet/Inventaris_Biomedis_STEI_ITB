<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PrinterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_store_new_printer(): void
    {
        $user = User::factory()->create();
        
        $printerData = [
            'name' => 'Ender 3 V2',
            'category' => 'filament',
            'status' => 'available',
        ];

        $response = $this->actingAs($user)
                         ->post('/printers', $printerData);

        $response->assertRedirect('/printers');
        
        $this->assertDatabaseHas('printers', [
            'name' => 'Ender 3 V2',
            'material_type_id' => 'filament' // Catatan: pastikan kolom ini benar di database, biasanya 'category' atau 'material_category'
        ]);
    }
}