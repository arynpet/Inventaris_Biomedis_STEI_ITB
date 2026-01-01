<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Login sebagai admin untuk akses
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_categories_index()
    {
        $response = $this->get(route('categories.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
    }

    #[Test]
    public function it_can_display_create_category_form()
    {
        $response = $this->get(route('categories.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('categories.create');
    }

    #[Test]
    public function it_can_store_new_category()
    {
        $categoryData = [
            'name' => 'Microcontroller',
            'description' => 'Arduino, ESP32, and others',
        ];

        $response = $this->post(route('categories.store'), $categoryData);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Microcontroller',
            'description' => 'Arduino, ESP32, and others',
        ]);
    }

    #[Test]
    public function it_can_display_edit_category_form()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('categories.edit');
    }

    #[Test]
    public function it_can_update_category()
    {
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
        ];

        $response = $this->put(route('categories.update', $category), $updateData);

        $response->assertRedirect(route('categories.index'));
        
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    #[Test]
    public function it_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->post(route('categories.store'), []);
        $response->assertSessionHasErrors(['name']);
    }
}