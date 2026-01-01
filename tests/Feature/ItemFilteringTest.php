<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ItemFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_filter_items_by_status()
    {
        Item::factory()->create(['status' => 'available', 'name' => 'Available Item']);
        Item::factory()->create(['status' => 'borrowed', 'name' => 'Borrowed Item']);
        Item::factory()->create(['status' => 'maintenance', 'name' => 'Maintenance Item']);

        $response = $this->get(route('items.index', ['status' => 'available']));

        $response->assertStatus(200);
        $response->assertSee('Available Item');
        $response->assertDontSee('Borrowed Item');
    }

    #[Test]
    public function it_can_filter_items_by_room()
    {
        $room1 = Room::factory()->create(['name' => 'Lab A']);
        $room2 = Room::factory()->create(['name' => 'Lab B']);

        Item::factory()->create(['room_id' => $room1->id, 'name' => 'Item in Lab A']);
        Item::factory()->create(['room_id' => $room2->id, 'name' => 'Item in Lab B']);

        $response = $this->get(route('items.index', ['room_id' => $room1->id]));

        $response->assertStatus(200);
        $response->assertSee('Item in Lab A');
        $response->assertDontSee('Item in Lab B');
    }

    #[Test]
    public function it_can_search_items_by_name()
    {
        Item::factory()->create(['name' => 'Arduino Uno']);
        Item::factory()->create(['name' => 'Raspberry Pi']);

        $response = $this->get(route('items.index', ['search' => 'Arduino']));

        $response->assertStatus(200);
        $response->assertSee('Arduino Uno');
        $response->assertDontSee('Raspberry Pi');
    }

    #[Test]
    public function it_can_search_items_by_serial_number()
    {
        Item::factory()->create(['serial_number' => 'SN-12345']);
        Item::factory()->create(['serial_number' => 'SN-67890']);

        $response = $this->get(route('items.index', ['search' => '12345']));

        $response->assertStatus(200);
        $response->assertSee('SN-12345');
        $response->assertDontSee('SN-67890');
    }

    #[Test]
    public function it_can_search_items_by_asset_number()
    {
        Item::factory()->create(['asset_number' => 'AST-001', 'name' => 'Item 1']);
        Item::factory()->create(['asset_number' => 'AST-002', 'name' => 'Item 2']);

        $response = $this->get(route('items.index', ['search' => 'AST-001']));

        $response->assertStatus(200);
        $response->assertSee('Item 1');
        $response->assertDontSee('Item 2');
    }

    #[Test]
    public function it_can_display_items_grouped_by_asset_number()
    {
        Item::factory()->create(['asset_number' => 'AST-100', 'name' => 'Item A']);
        Item::factory()->create(['asset_number' => 'AST-100', 'name' => 'Item B']);
        Item::factory()->create(['asset_number' => 'AST-200', 'name' => 'Item C']);

        $response = $this->get(route('items.index', ['group_by_asset' => '1']));

        $response->assertStatus(200);
        $response->assertViewIs('items.index_grouped');
    }

    #[Test]
    public function search_is_case_insensitive()
    {
        Item::factory()->create(['name' => 'Oscilloscope']);

        $response = $this->get(route('items.index', ['search' => 'oscilloscope']));

        $response->assertStatus(200);
        $response->assertSee('Oscilloscope');
    }
}