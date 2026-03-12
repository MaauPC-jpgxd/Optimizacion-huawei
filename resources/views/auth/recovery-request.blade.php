<x-guest-layout>
    <div class="max-w-md mx-auto">

        <h1 class="text-2xl font-bold mb-6 text-center">
            Recuperar Contraseña
        </h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('recovery.send-code') }}">
            @csrf

            <label class="block mb-2 font-medium">
                Ingresa tu email
            </label>

            <input
                type="email"
                name="email"
                required
                class="w-full border rounded px-3 py-2 mb-4"
                placeholder="correo@optimizame.com"
            >

            <button
                type="submit"
                  style="width:100%;
                  background:#2563eb;
                  color:withe;
                  padding:10px;
                  border:none; border-radius:6px; font-weight:bold;
                  cursor:pointer;
                  "
            >
               Solicitar Código
            </button>

        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                Cancelar
            </a>
        </div>

    </div>
</x-guest-layout>