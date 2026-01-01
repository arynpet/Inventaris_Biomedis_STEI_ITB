<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ItemQRCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function qr_code_is_generated_for_new_item()
    {
        $room = Room::factory()->create();

        $itemData = [
            'name' => 'Test Item',
            'serial_number' => 'SN-QR-001',
            'room_id' => $room->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
        ];

        $this->post(route('items.store'), $itemData);

        $item = Item::where('serial_number', 'SN-QR-001')->first();
        
        $this->assertNotNull($item->qr_code);
        $this->assertStringContainsString('qr/items/', $item->qr_code);
    }

    #[Test]
    public function qr_code_is_regenerated_when_item_details_change()
    {
        $item = Item::factory()->create();
        $oldQrCode = $item->qr_code;

        $updateData = [
            'name' => 'Updated Item Name',
            'asset_number' => 'NEW-ASSET',
            'serial_number' => 'NEW-SERIAL',
            'room_id' => $item->room_id,
            'quantity' => $item->quantity,
            'status' => $item->status,
            'condition' => $item->condition,
            'source' => $item->source,
            'acquisition_year' => $item->acquisition_year,
            'fiscal_group' => $item->fiscal_group,
        ];

        $this->put(route('items.update', $item), $updateData);

        $item->refresh();
        $this->assertNotEquals($oldQrCode, $item->qr_code);
    }

    #[Test]
    public function qr_code_pdf_can_be_generated()
    {
        $item = Item::factory()->create();

        $response = $this->get(route('items.qr.pdf', $item));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    #[Test]
    public function qr_code_contains_correct_item_information()
    {
        $room = Room::factory()->create(['name' => 'Lab 101']);
        
        $item = Item::factory()->create([
            'name' => 'Oscilloscope',
            'asset_number' => 'AST-123',
            'serial_number' => 'SN-456',
            'room_id' => $room->id,
            'condition' => 'good',
        ]);

        $this->assertNotNull($item->qr_code);
        
        $expectedPath = 'qr/items/' . $item->id . '.svg';
        $this->assertEquals($expectedPath, $item->qr_code);
    }

    #[Test]
    public function bulk_qr_regeneration_works()
    {
        Item::factory()->count(5)->create();

        $response = $this->post(route('items.regenerate_qr'));

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('success');

        $items = Item::all();
        foreach ($items as $item) {
            $this->assertNotNull($item->qr_code);
        }
    }

    #[Test]
    public function qr_scan_returns_correct_item_data()
    {
        $item = Item::factory()->create([
            'serial_number' => 'SN-SCAN-123',
            'status' => 'available',
        ]);

        $response = $this->postJson(route('borrowings.scan'), [
            'qr' => 'SN-SCAN-123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
            ],
        ]);
    }

    #[Test]
    public function qr_scan_fails_for_borrowed_item()
    {
        $item = Item::factory()->create([
            'serial_number' => 'SN-BORROWED',
            'status' => 'borrowed',
        ]);

        $response = $this->postJson(route('borrowings.scan'), [
            'qr' => 'SN-BORROWED',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
    }
}