<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\PeminjamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BorrowingValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function return_date_must_be_after_borrow_date()
    {
        $item = Item::factory()->create(['status' => 'available']);
        $user = PeminjamUser::factory()->create();

        $borrowData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
            'return_date' => now()->subDay()->format('Y-m-d'),
        ];

        $response = $this->post(route('borrowings.store'), $borrowData);

        $response->assertSessionHasErrors('return_date');
    }

    #[Test]
    public function cannot_borrow_already_borrowed_item()
    {
        $item = Item::factory()->create(['status' => 'available']);
        $user1 = PeminjamUser::factory()->create();
        $user2 = PeminjamUser::factory()->create();

        // First borrowing
        $this->post(route('borrowings.store'), [
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
        ]);

        // Try to borrow again
        $response = $this->post(route('borrowings.store'), [
            'user_id' => $user2->id,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('item_id');
    }

    #[Test]
    public function cannot_return_already_returned_item()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'returned']);

        $response = $this->put(route('borrowings.return', $borrowing), [
            'condition' => 'good',
        ]);

        $response->assertSessionHasErrors('error');
    }

    #[Test]
    public function condition_is_required_when_returning()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'borrowed']);

        $response = $this->put(route('borrowings.return', $borrowing), []);

        $response->assertSessionHasErrors('condition');
    }

    #[Test]
    public function condition_must_be_valid_enum()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'borrowed']);

        $response = $this->put(route('borrowings.return', $borrowing), [
            'condition' => 'invalid_condition',
        ]);

        $response->assertSessionHasErrors('condition');
    }

    #[Test]
    public function user_must_exist_when_creating_borrowing()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('borrowings.store'), [
            'user_id' => 99999,
            'item_id' => $item->id,
            'borrow_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('user_id');
    }

    #[Test]
    public function item_must_exist_when_creating_borrowing()
    {
        $user = PeminjamUser::factory()->create();

        $response = $this->post(route('borrowings.store'), [
            'user_id' => $user->id,
            'item_id' => 99999,
            'borrow_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('item_id');
    }
}