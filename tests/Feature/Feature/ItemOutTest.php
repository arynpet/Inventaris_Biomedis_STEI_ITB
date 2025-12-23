<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Item;
use App\Models\ItemOutLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ItemOutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_out_logs_index()
    {
        $response = $this->get(route('items.out.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('items.out_index');
    }

    #[Test]
    public function it_can_display_out_create_form()
    {
        $item = Item::factory()->create();

        $response = $this->get(route('items.out.create', $item));

        $response->assertStatus(200);
        $response->assertViewIs('items.out_form');
    }

    #[Test]
    public function it_can_process_item_out_with_file()
    {
        Storage::fake('public');
        
        $item = Item::factory()->create(['status' => 'available']);
        $file = UploadedFile::fake()->create('surat.pdf', 1000);

        $outData = [
            'recipient_name' => 'PT Test Company',
            'out_date' => now()->format('Y-m-d'),
            'reason' => 'Transfer to branch',
            'reference_file' => $file,
        ];

        $response = $this->post(route('items.out.store', $item), $outData);

        $response->assertRedirect(route('items.out.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'dikeluarkan',
        ]);

        $this->assertDatabaseHas('item_out_logs', [
            'item_id' => $item->id,
            'recipient_name' => 'PT Test Company',
        ]);

        Storage::disk('public')->assertExists('surat_keluar/' . $file->hashName());
    }

    #[Test]
    public function it_can_generate_out_pdf()
    {
        $item = Item::factory()->create(['status' => 'dikeluarkan']);
        $log = ItemOutLog::factory()->create(['item_id' => $item->id]);

        $response = $this->get(route('items.out.pdf', $item));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    #[Test]
    public function it_validates_out_data()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('items.out.store', $item), []);

        $response->assertSessionHasErrors(['recipient_name', 'out_date']);
    }
}