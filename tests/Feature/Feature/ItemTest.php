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

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_items_index_page()
    {
        $response = $this->get(route('items.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('items.index');
    }

    #[Test]
    public function it_can_create_a_new_item()
    {
        Storage::fake('public');
        
        $room = Room::factory()->create();
        $category = Category::factory()->create();

        $itemData = [
            'name' => 'Test Item',
            'asset_number' => 'AST-001',
            'serial_number' => 'SN-12345',
            'room_id' => $room->id,
            'quantity' => 5,
            'source' => 'Purchase',
            'acquisition_year' => 2024,
            'placed_in_service_at' => '2024-01-01',
            'fiscal_group' => 'Group A',
            'status' => 'available',
            'condition' => 'good',
            'categories' => [$category->id],
        ];

        $response = $this->post(route('items.store'), $itemData);

        $response->assertRedirect(route('items.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('items', [
            'name' => 'Test Item',
            'serial_number' => 'SN-12345',
        ]);
    }

    #[Test]
    public function it_can_update_an_existing_item()
    {
        $item = Item::factory()->create();

        $updateData = [
            'name' => 'Updated Item Name',
            'asset_number' => $item->asset_number,
            'serial_number' => $item->serial_number,
            'room_id' => $item->room_id,
            'quantity' => 10,
            'status' => 'maintenance',
            'condition' => 'damaged',
        ];

        $response = $this->put(route('items.update', $item), $updateData);

        $response->assertRedirect(route('items.index'));
        
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Updated Item Name',
            'status' => 'maintenance',
            'condition' => 'damaged',
        ]);
    }

    #[Test]
    public function it_can_delete_an_item()
    {
        $item = Item::factory()->create();

        $response = $this->delete(route('items.destroy', $item));

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    #[Test]
    public function it_generates_qr_code_on_item_creation()
    {
        Storage::fake('public');
        
        $room = Room::factory()->create();
        
        $itemData = [
            'name' => 'QR Test Item',
            'serial_number' => 'SN-QR-001',
            'room_id' => $room->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
        ];

        $this->post(route('items.store'), $itemData);

        $item = Item::where('serial_number', 'SN-QR-001')->first();
        
        $this->assertNotNull($item->qr_code);
        Storage::disk('public')->assertExists($item->qr_code);
    }

    #[Test]
    public function it_can_filter_items_by_status()
    {
        Item::factory()->create(['status' => 'available']);
        Item::factory()->create(['status' => 'borrowed']);

        $response = $this->get(route('items.index', ['status' => 'available']));

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_search_items()
    {
        Item::factory()->create(['name' => 'Searchable Item']);

        $response = $this->get(route('items.index', ['search' => 'Searchable']));

        $response->assertStatus(200);
        $response->assertSee('Searchable Item');
    }

    #[Test]
    public function it_validates_required_fields_on_create()
    {
        $response = $this->post(route('items.store'), []);

        $response->assertSessionHasErrors(['name', 'serial_number', 'room_id', 'status', 'condition']);
    }

    #[Test]
    public function serial_number_must_be_unique()
    {
        $existingItem = Item::factory()->create(['serial_number' => 'SN-UNIQUE']);

        $response = $this->post(route('items.store'), [
            'name' => 'Duplicate Serial',
            'serial_number' => 'SN-UNIQUE',
            'room_id' => Room::factory()->create()->id,
            'quantity' => 1,
            'status' => 'available',
            'condition' => 'good',
        ]);

        $response->assertSessionHasErrors('serial_number');
    }
}