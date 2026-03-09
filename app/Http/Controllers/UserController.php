<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Lista de usuarios (solo root)
    public function index()
    {
        $this->soloRoot();

        $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')->get();

        return view('admin.users.index', compact('users'));
    }

    // Formulario crear (solo root)
    public function create()
    {
        $this->soloRoot();

        return view('admin.users.create');
    }

    // Guardar usuario (solo root)
    public function store(Request $request)
    {
        $this->soloRoot();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => 'required|in:root,admin,lector',
            'status'   => 'required|in:activo,bloqueado',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // Función simple para validar solo root (sin Gate, directo y sin fallar)
    private function soloRoot()
    {
        $user = auth()->user();

        if (!$user || trim(strtolower($user->role ?? '')) !== 'root') {
            abort(403, 'acceso denegado.');
        }
    }
    public function edit(User $user)
{
    $this->soloRoot();  // o $this->authorize('manage-users') si usas Gate

    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, User $user)
{
    $this->soloRoot();  // o $this->authorize('manage-users')

    $request->validate([
        'name'   => 'required|string|max:255',
        'email'  => 'required|email|unique:users,email,' . $user->id,
        'role'   => 'required|in:root,admin,lector',
        'status' => 'required|in:activo,bloqueado',
        'password' => 'nullable|min:8|confirmed',
    ]);

    $data = $request->only('name', 'email', 'role', 'status');

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
}
 // Toggle estado (activo / bloqueado)
public function toggleStatus(User $user)
{
    $this->soloRoot();

    $user->status = $user->status === 'activo' ? 'bloqueado' : 'activo';
    $user->save();

    return redirect()->route('users.index')
        ->with('success', 'Estado actualizado: ahora está ' . $user->status . '.');
}

// Eliminar usuario
public function destroy(User $user)
{
    $this->soloRoot();

    // Opcional: evita eliminar al root principal si quieres
    if ($user->id === auth()->id()) {
        return redirect()->route('users.index')
            ->with('error', 'No puedes eliminar tu propia cuenta.');
    }

    $user->delete();

    return redirect()->route('users.index')
        ->with('success', 'Usuario eliminado correctamente.');
}   
}