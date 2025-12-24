<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MaterialType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_materials_index()
    {
        $response = $this->get(route('materials.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('materials.index');
    }

    #[Test]
    public function it_can_create_material_type()
    {
        $materialData = [
            'category' => 'filament',
            'name' => 'PLA Black',
            'stock_balance' => 1000,
            'unit' => 'gram',
        ];

        $response = $this->post(route('materials.store'), $materialData);

        $response->assertRedirect(route('materials.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('material_types', [
            'name' => 'PLA Black',
            'category' => 'filament',
            'stock_balance' => 1000,
        ]);
    }

    #[Test]
    public function it_can_update_material_type()
    {
        $material = MaterialType::factory()->create();

        $updateData = [
            'category' => $material->category,
            'name' => 'Updated Material Name',
            'stock_balance' => $material->stock_balance,
            'unit' => $material->unit,
        ];

        $response = $this->put(route('materials.update', $material), $updateData);

        $response->assertRedirect(route('materials.index'));

        $this->assertDatabaseHas('material_types', [
            'id' => $material->id,
            'name' => 'Updated Material Name',
        ]);
    }

    #[Test]
    public function it_can_add_stock_to_material()
    {
        $material = MaterialType::factory()->create(['stock_balance' => 500]);

        $response = $this->post(route('materials.addStock', $material), [
            'amount' => 100,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('material_types', [
            'id' => $material->id,
            'stock_balance' => 600,
        ]);
    }

    #[Test]
    public function it_validates_material_data()
    {
        $response = $this->post(route('materials.store'), []);

        $response->assertSessionHasErrors([
            'category',
            'name',
            'stock_balance',
            'unit',
        ]);
    }
}