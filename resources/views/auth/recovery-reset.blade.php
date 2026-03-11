<x-guest-layout>
<form method="POST" action="/recovery/reset-password">
    @csrf

   <input type="email" name="email" value="{{ session('email') }}" readonly>

    <input type="text" name="code" placeholder="Código">

    <input type="password" name="password" placeholder="Nueva contraseña">

    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">

    <button type="submit">Cambiar contraseña</button>
</form>
</x-guest-layout>