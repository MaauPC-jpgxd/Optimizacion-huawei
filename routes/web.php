<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;  
use App\Http\Controllers\InventarioSucursalController;
use App\Http\Controllers\VentaDetalleController;
use App\Http\Controllers\passwordRecoveryController;
// Página de bienvenida pública (sin login)
Route::get('/', function () {
    return view('bienvenida');
});
// Redirección inteligente del /dashboard según rol del usuario autenticado
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect('/login');
    }

    if ($user->role === 'root') {
        return redirect()->route('dashboard.root');
    }

    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    }
    // lector por defecto
    return redirect()->route('dashboard.lector');
})->middleware(['auth', 'verified'])->name('dashboard');
// Dashboards específicos por rol (protegidos con auth)
Route::middleware(['auth', 'verified'])->group(function () {
    // Root dashboard
    Route::get('/dashboard/root', function () {
        return view('dashboards.root');
    })->name('dashboard.root');
    // Admin dashboard
    Route::get('/dashboard/admin', function () {
        return view('dashboards.admin');
    })->name('dashboard.admin');
    // Lector dashboard (el más restrictivo)
    Route::get('/dashboard/lector', function () {
        return view('dashboard.lector');
    })->name('dashboards.lector');
    // Rutas de perfil (ya las tenías)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            // Eliminar usuario
     Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
     Route::get('/dashboard/lector', function () {
        return view('dashboards.lector');
    })->name('dashboard.lector');
    // Solo root puede editar
    Route::prefix('inventario')->name('inventario.')->group(function () {
        Route::get('/', [InventarioSucursalController::class, 'index'])->name('index');
        // Solo root puede editar (usa tu método soloRoot en el controlador)
        Route::get('/{id}/edit', [InventarioSucursalController::class, 'edit'])->name('edit');
        // Route::put('/{id}', [InventarioSucursalController::class, 'update'])->name('update');
    });
    Route::get('/inventario/excel', [InventarioSucursalController::class, 'exportExcel'])->name('inventario.excel');
    Route::get('/inventario/pdf', [InventarioSucursalController::class, 'exportPDF'])->name('inventario.pdf');
Route::get('/ventas-huawei', [VentaDetalleController::class, 'index'])
    ->name('ventas.huawei');
    Route::get('/ventas-huawei/excel', [VentaDetalleController::class, 'exportExcel'])
    ->name('ventas.huawei.excel');
Route::get('/ventas-huawei/pdf', [VentaDetalleController::class, 'exportPdf'])
    ->name('ventas.huawei.pdf');
//Mostrar formulario para ingresar correo
Route::get('/recovery', function () {
    //return view('auth.recovery-request');
    dd('si jala xd');
})->name('recovery.request');
// en routes/web.php
Route::get('/recovery/reset', function () {
    return view('auth.recovery-reset');
})->name('recovery.reset.form');

});
Route::get('/recovery', function () {
    return view('auth.recovery-request');
    //dd('si jala xd');
})->name('recovery.request');
// Enviar código
Route::post('/recovery/send-code', [passwordRecoveryController::class, 'sendCode'])->name('recovery.send-code');
//esta ruta se utilizara mas adelante no la borres pero menos la uses xd
//Route::get('/password/forgot', [NewPasswordController::class, 'showEmailForm'])->name('password.request');
//formulario para cambiar la contraseña
Route::get('/recovery/reset', function () {
    return view('auth.recovery-reset');
})->name('recovery.reset.form');

Route::post('/recovery/reset-password',
    [passwordRecoveryController::class, 'resetPassword']
)->name('recovery.reset');
//pantalla de cambio exitoso
Route::get('/exito', function () {
    return view('auth.pasword-confirmation');
})->name('pasword.confirmation');
// Rutas de autenticación (ogin, register, etc.) que vienen de auth.php
//no borres la de abajo se cae todo xd
require __DIR__.'/auth.php';