<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Room;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_rooms_index()
    {
        $response = $this->get(route('rooms.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('rooms.index');
    }

    #[Test]
    public function it_can_create_a_room()
    {
        $roomData = [
            'code' => 'LAB-001',
            'name' => 'Lab Biomedis',
            'description' => 'Laboratory for biomedical research',
            'status' => 'sedia',
        ];

        $response = $this->post(route('rooms.store'), $roomData);

        $response->assertRedirect(route('rooms.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rooms', [
            'code' => 'LAB-001',
            'name' => 'Lab Biomedis',
        ]);
    }

    #[Test]
    public function it_can_update_a_room()
    {
        $room = Room::factory()->create();

        $updateData = [
            'code' => $room->code,
            'name' => 'Updated Room Name',
            'description' => 'Updated description',
            'status' => 'dipinjam',
        ];

        $response = $this->put(route('rooms.update', $room), $updateData);

        $response->assertRedirect(route('rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'name' => 'Updated Room Name',
            'status' => 'dipinjam',
        ]);
    }

    #[Test]
    public function it_can_delete_a_room()
    {
        $room = Room::factory()->create();

        $response = $this->delete(route('rooms.destroy', $room));

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    #[Test]
    public function it_can_move_item_between_rooms()
    {
        $room1 = Room::factory()->create();
        $room2 = Room::factory()->create();
        $item = Item::factory()->create(['room_id' => $room1->id]);

        $response = $this->post(route('rooms.moveItem'), [
            'item_id' => $item->id,
            'new_room_id' => $room2->id,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'room_id' => $room2->id,
        ]);
    }

    #[Test]
    public function it_validates_room_code_uniqueness()
    {
        Room::factory()->create(['code' => 'UNIQUE-CODE']);

        $response = $this->post(route('rooms.store'), [
            'code' => 'UNIQUE-CODE',
            'name' => 'Duplicate Code Room',
            'status' => 'sedia',
        ]);

        // Controller has try-catch, validation becomes error flash
        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    #[Test]
    public function it_displays_items_in_room_detail()
    {
        $room = Room::factory()->create();
        Item::factory()->count(3)->create(['room_id' => $room->id]);

        $response = $this->get(route('rooms.show', $room));

        $response->assertStatus(200);
        $response->assertViewHas('room');
    }
}