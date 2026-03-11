<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RecoveryCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendRecoveryCode; // Clase de correo que crearemos

class PasswordRecoveryController extends Controller {
    // 🔹 PASO 1: Enviar código de recuperación al correo
    public function sendCode(Request $request) {
        // Validar que el correo exista
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Obtener el usuario
        $user = User::where('email', $request->email)->first();

        // Eliminar códigos anteriores del usuario (evitar duplicados)
        RecoveryCode::where('user_id', $user->id)->delete();

        // Generar y guardar el nuevo código
        $code = RecoveryCode::generateCode();
        RecoveryCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addHour(), // Ya configurado en la migración, pero lo repetimos por seguridad
            'is_active' => 1
        ]);

        // Enviar código por correo
        Mail::to($user->email)->send(new SendRecoveryCode($code));

       /* return response()->json([
            'message' => '✅ Código de recuperación enviado a tu correo electrónico'
        ], 200);*/
        return redirect()->route('recovery.reset.form')->with('email',$request->email);
    }

    // 🔹 PASO 2: Validar código y cambiar contraseña
    public function resetPassword(Request $request) {
        // Validar datos
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric|digits:6',
            'password' => 'required|string|confirmed|min:8' // password_confirmation es obligatorio
        ]);

        $user = User::where('email', $request->email)->first();

        // Buscar código válido (activo y no expirado)
        $recoveryCode = RecoveryCode::valid()
                                    ->where('user_id', $user->id)
                                    ->where('code', $request->code)
                                    ->first();

        if (!$recoveryCode) {
            return response()->json([
                'error' => '❌ Código inválido, expirado o ya usado'
            ], 400);
        }

        // Actualizar contraseña
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        // Inactivar y eliminar el código
        $recoveryCode->update(['is_active' => 0]);
        $recoveryCode->delete();

        return response()->json([
            'message' => '✅ Contraseña actualizada exitosamente'
        ], 200);
    }
}
