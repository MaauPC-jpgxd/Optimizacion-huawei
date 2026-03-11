<x-guest-layout>
    <h1>Recuperar Contraseña</h1>
    <form method="POST" action="{{ route('recovery.send-code') }}">
        @csrf
        <label for="email">Ingresa tu email:</label>
        <input type="email" name="email" required>
        <button type="submit">Enviar Código</button>
    </form>
</x-guest-layout>