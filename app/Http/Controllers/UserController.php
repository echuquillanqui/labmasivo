<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::query()->orderBy('name')->paginate(20);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['sello_digital'] = $request->file('sello_digital')?->store('sellos-usuarios', 'public');
        User::create($datos);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $this->validar($request, $usuario);
        $selloAnterior = $usuario->sello_digital;

        if ($request->hasFile('sello_digital')) {
            $datos['sello_digital'] = $request->file('sello_digital')->store('sellos-usuarios', 'public');
        }

        $usuario->update($datos);

        if (isset($datos['sello_digital']) && $selloAnterior) {
            Storage::disk('public')->delete($selloAnterior);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($request->user()->is($usuario)) {
            return back()->with('error', 'No puedes eliminar tu propio usuario mientras tienes la sesión iniciada.');
        }

        $sello = $usuario->sello_digital;
        $usuario->delete();
        if ($sello) {
            Storage::disk('public')->delete($sello);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function validar(Request $request, ?User $usuario = null): array
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($usuario)],
            'password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'sello_digital' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        if (blank($datos['password'] ?? null)) {
            unset($datos['password']);
        }

        return $datos;
    }
}
