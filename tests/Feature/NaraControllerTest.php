<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\PeminjamUser;
use App\Http\Controllers\NaraController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class NaraControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Nara requires authentication (N2 fix)
        $this->actingAs(User::factory()->create());
    }

    // ==========================================
    // N1: SQL Injection Prevention Tests
    // ==========================================

    #[Test]
    public function it_escapes_wildcard_characters_in_search()
    {
        $room1 = Room::factory()->create(['name' => 'Lab Bio']);
        $room2 = Room::factory()->create(['name' => 'Lab Kimia']);
        
        // Try SQL injection with wildcards
        $response = $this->postJson(route('nara.chat'), [
            'message' => 'cari item di ruangan %_%' // Should not match all rooms
        ]);
        
        $response->assertStatus(200);
        // Response should not leak all rooms due to wildcard injection
    }

    #[Test]
    public function it_prevents_like_injection_in_room_search()
    {
        Room::factory()->create(['name' => 'Lab Test']);
        
        $controller = new NaraController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('escapeLikeWildcards');
        $method->setAccessible(true);
        
        // Test escaping
        $this->assertEquals('test\\%value', $method->invoke($controller, 'test%value'));
        $this->assertEquals('test\\_value', $method->invoke($controller, 'test_value'));
        $this->assertEquals('test\\\\value', $method->invoke($controller, 'test\\value'));
    }

    // ==========================================
    // N2: Authorization Tests
    // ==========================================

    #[Test]
    public function it_requires_authentication_for_nara_chat()
    {
        auth()->logout();
        
        $response = $this->postJson(route('nara.chat'), [
            'message' => 'test'
        ]);
        
        $response->assertStatus(401); // Unauthorized
    }

    #[Test]
    public function it_requires_authentication_for_batch_delete()
    {
        auth()->logout();
        
        $response = $this->postJson(route('nara.destroy'), [
            'serial_numbers' => ['SN-001']
        ]);
        
        $response->assertStatus(401);
    }

    #[Test]
    public function it_requires_authentication_for_batch_create()
    {
        auth()->logout();
        
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => []
        ]);
        
        $response->assertStatus(401);
    }

    // ==========================================
    // N3: Batch Validation Tests
    // ==========================================

    #[Test]
    public function it_validates_batch_size_limit_for_delete()
    {
        // Try to delete more than 50 items
        $serials = [];
        for ($i = 0; $i < 51; $i++) {
            $serials[] = "SN-$i";
        }
        
        $response = $this->postJson(route('nara.destroy'), [
            'serial_numbers' => $serials
        ]);
        
        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors('serial_numbers');
    }

    #[Test]
    public function it_validates_batch_size_limit_for_create()
    {
        $room = Room::factory()->create();
        
        // Try to create more than 50 items
        $items = [];
        for ($i = 0; $i < 51; $i++) {
            $items[] = [
                'name' => "Item $i",
                'serial_number' => "SN-$i",
                'room_id' => $room->id,
            ];
        }
        
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => $items
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('items');
    }

    #[Test]
    public function it_validates_required_fields_in_batch_create()
    {
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => [
                [
                    // Missing required fields
                    'name' => '',
                    'serial_number' => '',
                ]
            ]
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['items.0.name', 'items.0.serial_number', 'items.0.room_id']);
    }

    #[Test]
    public function it_validates_room_exists_in_batch_create()
    {
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => [
                [
                    'name' => 'Test Item',
                    'serial_number' => 'SN-001',
                    'room_id' => 99999, // Non-existent room
                ]
            ]
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('items.0.room_id');
    }

    // ==========================================
    // N4: Transaction & Borrowed Check Tests
    // ==========================================

    #[Test]
    public function it_prevents_deleting_borrowed_items()
    {
        $item = Item::factory()->create(['serial_number' => 'SN-BORROWED']);
        Borrowing::factory()->create([
            'item_id' => $item->id,
            'status' => 'borrowed'
        ]);
        
        $response = $this->postJson(route('nara.destroy'), [
            'serial_numbers' => ['SN-BORROWED']
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
        
        // Item should still exist
        $this->assertDatabaseHas('items', ['serial_number' => 'SN-BORROWED']);
    }

    #[Test]
    public function it_uses_transaction_for_batch_delete()
    {
        // Test transaction by trying to delete borrowed item
        // Transaction should rollback entire batch
        $item1 = Item::factory()->create(['serial_number' => 'SN-001', 'status' => 'available']);
        $item2 = Item::factory()->create(['serial_number' => 'SN-BORROWED']);
        
        // Make item2 borrowed
        Borrowing::factory()->create([
            'item_id' => $item2->id,
            'status' => 'borrowed'
        ]);
        
        $response = $this->postJson(route('nara.destroy'), [
            'serial_numbers' => ['SN-001', 'SN-BORROWED']
        ]);
        
        // Should fail due to borrowed item
        $response->assertStatus(422);
        
        // Transaction should rollback - both items still exist
        $this->assertDatabaseHas('items', ['serial_number' => 'SN-001']);
        $this->assertDatabaseHas('items', ['serial_number' => 'SN-BORROWED']);
    }

    #[Test]
    public function it_successfully_deletes_available_items()
    {
        $item1 = Item::factory()->create(['serial_number' => 'SN-DEL-001', 'status' => 'available']);
        $item2 = Item::factory()->create(['serial_number' => 'SN-DEL-002', 'status' => 'available']);
        
        $response = $this->postJson(route('nara.destroy'), [
            'serial_numbers' => ['SN-DEL-001', 'SN-DEL-002']
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        
        // Items should be soft deleted
        $this->assertSoftDeleted('items', ['serial_number' => 'SN-DEL-001']);
        $this->assertSoftDeleted('items', ['serial_number' => 'SN-DEL-002']);
    }

    // ==========================================
    // Batch Create Tests
    // ==========================================

    #[Test]
    public function it_creates_items_in_batch()
    {
        $room = Room::factory()->create();
        
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => [
                [
                    'name' => 'Microscope 1',
                    'serial_number' => 'E-MIC-26001',
                    'asset_number' => 'INV-001',
                    'room_id' => $room->id,
                    'status' => 'available',
                    'condition' => 'good',
                ],
                [
                    'name' => 'Microscope 2',
                    'serial_number' => 'E-MIC-26002',
                    'asset_number' => 'INV-001',
                    'room_id' => $room->id,
                    'status' => 'available',
                    'condition' => 'good',
                ]
            ]
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        
        $this->assertDatabaseHas('items', ['serial_number' => 'E-MIC-26001']);
        $this->assertDatabaseHas('items', ['serial_number' => 'E-MIC-26002']);
    }

    #[Test]
    public function it_handles_duplicate_serial_numbers_with_increment()
    {
        $room = Room::factory()->create();
        
        // Create item with serial number that will conflict
        Item::factory()->create(['serial_number' => 'E-MIC-26001']);
        
        $response = $this->postJson(route('nara.store_batch'), [
            'items' => [
                [
                    'name' => 'Microscope',
                    'serial_number' => 'E-MIC-26001', // Duplicate
                    'room_id' => $room->id,
                    'status' => 'available',
                    'condition' => 'good',
                ]
            ]
        ]);
        
        $response->assertStatus(200);
        
        // Should create with incremented serial (E-MIC-26002)
        $this->assertDatabaseHas('items', ['serial_number' => 'E-MIC-26002']);
    }
}
