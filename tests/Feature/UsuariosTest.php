<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuariosTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_crud_de_usuarios_requiere_autenticacion(): void
    {
        $this->get('/usuarios')->assertRedirect('/login');
        $this->get('/usuarios/create')->assertRedirect('/login');
        $this->post('/usuarios', [])->assertRedirect('/login');
    }

    public function test_un_usuario_autenticado_puede_crear_editar_y_eliminar_otro_usuario(): void
    {
        $administrador = User::factory()->create();

        $this->actingAs($administrador)->post('/usuarios', [
            'name' => 'Usuario de laboratorio',
            'email' => 'laboratorio@example.com',
            'password' => 'clave-segura',
            'password_confirmation' => 'clave-segura',
        ])->assertRedirect(route('usuarios.index'));

        $usuario = User::where('email', 'laboratorio@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('clave-segura', $usuario->password));

        $this->put(route('usuarios.update', $usuario), [
            'name' => 'Usuario actualizado',
            'email' => 'actualizado@example.com',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseHas('users', ['id' => $usuario->id, 'name' => 'Usuario actualizado']);
        $this->delete(route('usuarios.destroy', $usuario))->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
    }

    public function test_un_usuario_no_puede_eliminarse_a_si_mismo(): void
    {
        $usuario = User::factory()->create();

        $this->actingAs($usuario)->delete(route('usuarios.destroy', $usuario))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $usuario->id]);
    }
}
