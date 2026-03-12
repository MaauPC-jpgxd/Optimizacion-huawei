<x-guest-layout>
    <div style="max-width:400px;margin:auto">

        <h1 style="font-size:24px;font-weight:bold;margin-bottom:20px;text-align:center">
            Cambiar Contraseña
        </h1>

        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:10px;border-radius:6px;margin-bottom:15px">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/recovery/reset-password">
            @csrf

            <label style="display:block;margin-bottom:5px">Correo</label>
            <input 
                type="email"
                name="email"
                value="{{ session('email') }}"
                readonly
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;margin-bottom:12px"
            >

            <label style="display:block;margin-bottom:5px">Código</label>
            <input 
                type="text"
                name="code"
                placeholder="Código de recuperación"
                required
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;margin-bottom:12px"
            >

            <label style="display:block;margin-bottom:5px">Nueva contraseña</label>
            <input 
                type="password"
                name="password"
                placeholder="Nueva contraseña"
                required
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;margin-bottom:12px"
            >

            <label style="display:block;margin-bottom:5px">Confirmar contraseña</label>
            <input 
                type="password"
                name="password_confirmation"
                placeholder="Confirmar contraseña"
                required
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:5px;margin-bottom:20px"
            >

            <button
                type="submit"
                style="width:100%;background:#2563eb;color:white;padding:10px;border:none;border-radius:6px;font-weight:bold;cursor:pointer"
            >
                Cambiar contraseña
            </button>

        </form>

    </div>
</x-guest-layout>