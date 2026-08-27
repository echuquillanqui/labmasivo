<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function test_se_puede_cargar_y_reemplazar_el_sello_digital_de_un_usuario(): void
    {
        Storage::fake('public');
        $administrador = User::factory()->create();

        $this->actingAs($administrador)->post('/usuarios', [
            'name' => 'Usuario con sello',
            'email' => 'sello@example.com',
            'password' => 'clave-segura',
            'password_confirmation' => 'clave-segura',
            'sello_digital' => UploadedFile::fake()->image('sello.png'),
        ])->assertRedirect(route('usuarios.index'));

        $usuario = User::where('email', 'sello@example.com')->firstOrFail();
        $selloAnterior = $usuario->sello_digital;
        $this->assertNotNull($selloAnterior);
        Storage::disk('public')->assertExists($selloAnterior);

        $this->actingAs($administrador)->put(route('usuarios.update', $usuario), [
            'name' => $usuario->name,
            'email' => $usuario->email,
            'password' => '',
            'password_confirmation' => '',
            'sello_digital' => UploadedFile::fake()->image('sello-nuevo.jpg'),
        ])->assertRedirect(route('usuarios.index'));

        $selloNuevo = $usuario->fresh()->sello_digital;
        $this->assertNotSame($selloAnterior, $selloNuevo);
        Storage::disk('public')->assertMissing($selloAnterior);
        Storage::disk('public')->assertExists($selloNuevo);
    }

    public function test_el_formulario_del_crud_muestra_el_campo_para_el_sello(): void
    {
        $administrador = User::factory()->create();

        $this->actingAs($administrador)->get(route('usuarios.create'))
            ->assertOk()
            ->assertSee('Sello digital')
            ->assertSee('name="sello_digital"', false);
    }
}
