<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'email'],
        'code' => ['required', 'string'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Buscar usuario
    $user = User::where('email', $request->email)->first();

    // Buscar código válido
    $recoveryCode = RecoveryCode::valid()
        ->where('user_id', $user->id)
        ->where('code', $request->code)
        ->first();

    if (!$recoveryCode) {
        return back()->withErrors(['error' => 'Código inválido o expirado'])->withInput();
    }

    // Actualizar contraseña
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Inactivar y eliminar código
    $recoveryCode->update(['is_active' => 0]);
    $recoveryCode->delete();

    return redirect()->route('login')->with('status', 'Contraseña actualizada correctamente.');
}
}
