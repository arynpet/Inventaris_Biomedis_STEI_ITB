<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Room;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ItemBatchOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_create_items_in_batch_mode()
    {
        $room = Room::factory()->create();
        $category = Category::factory()->create();

        $batchData = [
            'input_mode' => 'batch',
            'name' => 'Arduino',
            'asset_number' => 'ARD-001',
            'room_id' => $room->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
            'source' => 'Purchase',
            'acquisition_year' => 2024,
            'fiscal_group' => '1',
            'categories' => [$category->id],
            'serial_numbers_batch' => "SN-001\nSN-002\nSN-003",
        ];

        $response = $this->post(route('items.store'), $batchData);

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(3, Item::count());
        
        $this->assertDatabaseHas('items', ['name' => 'Arduino 1', 'serial_number' => 'SN-001']);
        $this->assertDatabaseHas('items', ['name' => 'Arduino 2', 'serial_number' => 'SN-002']);
        $this->assertDatabaseHas('items', ['name' => 'Arduino 3', 'serial_number' => 'SN-003']);
    }

    #[Test]
    public function batch_mode_validates_duplicate_serial_numbers()
    {
        Item::factory()->create(['serial_number' => 'SN-DUP']);

        $room = Room::factory()->create();

        $batchData = [
            'input_mode' => 'batch',
            'name' => 'Test Item',
            'room_id' => $room->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
            'serial_numbers_batch' => "SN-DUP\nSN-NEW",
        ];

        $response = $this->post(route('items.store'), $batchData);

        $response->assertSessionHasErrors('serial_numbers_batch');
    }

    #[Test]
    public function it_can_bulk_delete_items()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->post(route('items.bulk_action'), [
            'selected_ids' => $items->pluck('id')->toArray(),
            'action_type' => 'delete',
        ]);

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(0, Item::count());
    }

    #[Test]
    public function it_can_bulk_copy_items()
    {
        $item = Item::factory()->create(['name' => 'Original Item']);

        $response = $this->post(route('items.bulk_action'), [
            'selected_ids' => [$item->id],
            'action_type' => 'copy',
        ]);

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(2, Item::count());
        
        $copiedItem = Item::where('id', '!=', $item->id)->first();
        $this->assertStringContainsString('Original Item', $copiedItem->name);
        $this->assertStringContainsString('-CPY-', $copiedItem->serial_number);
    }

    #[Test]
    public function bulk_action_validates_selected_ids()
    {
        $response = $this->post(route('items.bulk_action'), [
            'selected_ids' => [],
            'action_type' => 'delete',
        ]);

        $response->assertSessionHasErrors('selected_ids');
    }

    #[Test]
    public function batch_items_share_same_asset_number()
    {
        $room = Room::factory()->create();

        $batchData = [
            'input_mode' => 'batch',
            'name' => 'Sensor',
            'asset_number' => 'SENS-100',
            'room_id' => $room->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
            'serial_numbers_batch' => "SN-A\nSN-B",
        ];

        $this->post(route('items.store'), $batchData);

        $items = Item::all();
        foreach ($items as $item) {
            $this->assertEquals('SENS-100', $item->asset_number);
        }
    }
}