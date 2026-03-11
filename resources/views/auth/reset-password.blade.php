<x-guest-layout>
   <form method="POST" action="{{ route('recovery.reset') }}">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}" />
    <div>
        <label for="code">Código recibido:</label>
        <input type="text" name="code" required />
    </div>
    <!-- campos de contraseña -->
    <div>
        <label for="password">Nueva contraseña:</label>
        <input type="password" name="password" required />
    </div>
    <div>
        <label for="password_confirmation">Confirmar contraseña:</label>
        <input type="password" name="password_confirmation" required />
    </div>
    <button type="submit">Cambiar Contraseña</button>
</form>
</x-guest-layout>
