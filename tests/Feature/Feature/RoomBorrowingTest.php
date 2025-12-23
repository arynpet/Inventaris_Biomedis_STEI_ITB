<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\RoomBorrowing;
use App\Models\Room;
use App\Models\PeminjamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class RoomBorrowingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_room_borrowings_index()
    {
        $response = $this->get(route('room_borrowings.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('room_borrowings.index');
    }

    #[Test]
    public function it_can_display_create_room_borrowing_form()
    {
        $response = $this->get(route('room_borrowings.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('room_borrowings.create');
    }

    #[Test]
    public function it_can_store_new_room_borrowing()
    {
        $room = Room::factory()->create();
        $user = PeminjamUser::factory()->create();

        $borrowData = [
            'room_id' => $room->id,
            'user_id' => $user->id,
            'start_time' => now()->addHour()->format('Y-m-d H:i:s'),
            'end_time' => now()->addHours(3)->format('Y-m-d H:i:s'),
        ];

        $response = $this->post(route('room_borrowings.store'), $borrowData);

        $response->assertRedirect(route('room_borrowings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('room_borrowings', [
            'room_id' => $room->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_can_update_room_borrowing()
    {
        $borrowing = RoomBorrowing::factory()->create();
        
        // Data baru untuk update
        $newEnd = now()->addHours(5)->format('Y-m-d H:i:s');

        $updateData = [
            'room_id' => $borrowing->room_id,
            'user_id' => $borrowing->user_id,
            'start_time' => $borrowing->start_time->format('Y-m-d H:i:s'),
            'end_time' => $newEnd,
        ];

        $response = $this->put(route('room_borrowings.update', $borrowing), $updateData);

        $response->assertRedirect(route('room_borrowings.index'));
        
        // Verifikasi database menggunakan format tanggal yang konsisten
        // Kita cek manual karena format datetime database mungkin berbeda presisi
        $updatedBorrowing = RoomBorrowing::find($borrowing->id);
        $this->assertEquals($newEnd, $updatedBorrowing->end_time->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function it_can_delete_room_borrowing()
    {
        $borrowing = RoomBorrowing::factory()->create();

        $response = $this->delete(route('room_borrowings.destroy', $borrowing));

        $response->assertRedirect(route('room_borrowings.index'));
        $this->assertDatabaseMissing('room_borrowings', ['id' => $borrowing->id]);
    }

    #[Test]
    public function it_validates_room_borrowing_data()
    {
        $response = $this->post(route('room_borrowings.store'), []);
        
        $response->assertSessionHasErrors(['room_id', 'user_id', 'start_time', 'end_time']);
    }

    #[Test]
    public function end_time_must_be_after_start_time()
    {
        $room = Room::factory()->create();
        $user = PeminjamUser::factory()->create();

        $borrowData = [
            'room_id' => $room->id,
            'user_id' => $user->id,
            'start_time' => now()->format('Y-m-d H:i:s'),
            'end_time' => now()->subHour()->format('Y-m-d H:i:s'), // Error: End sebelum Start
        ];

        $response = $this->post(route('room_borrowings.store'), $borrowData);
        
        $response->assertSessionHasErrors('end_time');
    }
}