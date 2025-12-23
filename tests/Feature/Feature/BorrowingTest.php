<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_borrowings_index()
    {
        $response = $this->get(route('borrowings.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('borrowings.index');
    }

    #[Test]
    public function it_can_create_a_borrowing()
    {
        $item = Item::factory()->create(['status' => 'available']);
        $user = PeminjamUser::factory()->create();

        $borrowData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
            'return_date' => now()->addDays(7)->format('Y-m-d'),
            'notes' => 'Test borrowing',
        ];

        $response = $this->post(route('borrowings.store'), $borrowData);

        $response->assertRedirect(route('borrowings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('borrowings', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'borrowed',
        ]);
    }

    #[Test]
    public function it_cannot_borrow_unavailable_item()
    {
        $item = Item::factory()->create(['status' => 'borrowed']);
        $user = PeminjamUser::factory()->create();

        $borrowData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('borrowings.store'), $borrowData);

        $response->assertSessionHasErrors('item_id');
    }

    #[Test]
    public function it_can_return_borrowed_item()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'borrowed']);

        $returnData = [
            'condition' => 'good',
        ];

        $response = $this->put(route('borrowings.return', $borrowing), $returnData);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'returned',
            'return_condition' => 'good',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $borrowing->item_id,
            'status' => 'available',
            'condition' => 'good',
        ]);
    }

    #[Test]
    public function returning_damaged_item_sets_maintenance_status()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'borrowed']);

        $response = $this->put(route('borrowings.return', $borrowing), [
            'condition' => 'damaged',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $borrowing->item_id,
            'status' => 'maintenance',
            'condition' => 'damaged',
        ]);
    }

    #[Test]
    public function it_can_display_borrowing_history()
    {
        Borrowing::factory()->create(['status' => 'returned']);

        $response = $this->get(route('borrowings.history'));

        $response->assertStatus(200);
        $response->assertViewIs('borrowings.history');
    }

    #[Test]
    public function it_can_generate_history_pdf()
    {
        Borrowing::factory()->count(3)->create(['status' => 'returned']);

        $response = $this->get(route('borrowings.history.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    #[Test]
    public function it_can_scan_qr_for_borrowing()
    {
        $item = Item::factory()->create([
            'serial_number' => 'SN-QR-TEST',
            'status' => 'available',
        ]);

        $response = $this->postJson(route('borrowings.scan'), [
            'qr' => 'SN-QR-TEST',
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
}