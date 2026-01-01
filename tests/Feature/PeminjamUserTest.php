<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PeminjamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PeminjamUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_can_display_peminjam_users_index()
    {
        $response = $this->get(route('peminjam-users.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('peminjam-users.index');
    }

    #[Test]
    public function it_can_create_peminjam_user()
    {
        $userData = [
            'name' => 'John Doe',
            'nim' => '12345678',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'role' => 'mahasiswa',
            'is_trained' => true,
        ];

        $response = $this->post(route('peminjam-users.store'), $userData);

        $response->assertRedirect(route('peminjam-users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjam_users', [
            'name' => 'John Doe',
            'nim' => '12345678',
            'is_trained' => true,
        ]);
    }

    #[Test]
    public function it_can_update_peminjam_user()
    {
        $user = PeminjamUser::factory()->create(['is_trained' => false]);

        $updateData = [
            'name' => $user->name,
            'nim' => $user->nim,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => 'dosen',
            'is_trained' => true,
        ];

        $response = $this->put(route('peminjam-users.update', $user), $updateData);

        $response->assertRedirect(route('peminjam-users.index'));

        $this->assertDatabaseHas('peminjam_users', [
            'id' => $user->id,
            'role' => 'dosen',
            'is_trained' => true,
        ]);
    }

    #[Test]
    public function it_can_delete_peminjam_user()
    {
        $user = PeminjamUser::factory()->create();

        $response = $this->delete(route('peminjam-users.destroy', $user));

        $response->assertRedirect(route('peminjam-users.index'));
        $this->assertDatabaseMissing('peminjam_users', ['id' => $user->id]);
    }
}