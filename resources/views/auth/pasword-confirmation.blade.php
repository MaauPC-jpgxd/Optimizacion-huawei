<x-guest-layout>

<div style="display:flex;justify-content:center;align-items:center;min-height:100vh">

    <div style="background:white;padding:40px;border-radius:10px;width:420px;text-align:center;box-shadow:0 10px 25px rgba(0,0,0,0.1)">

        <!-- Check animado -->
        <div style="display:flex;justify-content:center;margin-bottom:25px">
            <svg class="checkmark" viewBox="0 0 52 52" width="90" height="90">

                <circle 
                    cx="26"
                    cy="26"
                    r="25"
                    fill="none"
                    stroke="#22c55e"
                    stroke-width="3"
                    stroke-dasharray="166"
                    stroke-dashoffset="166"
                    class="circle"
                />

                <path
                    d="M14 27l7 7 16-16"
                    fill="none"
                    stroke="#22c55e"
                    stroke-width="3"
                    stroke-dasharray="48"
                    stroke-dashoffset="48"
                    class="check"
                />

            </svg>
        </div>

        <h1 style="font-size:24px;font-weight:bold;margin-bottom:10px">
            ¡Contraseña Cambiada!
        </h1>

        <p style="color:#6b7280;margin-bottom:25px">
            Tu contraseña ha sido actualizada exitosamente.
        </p>

        <a href="{{ route('login') }}"
           style="display:inline-block;background:#2563eb;color:white;padding:12px 25px;border-radius:6px;font-weight:bold;text-decoration:none">
            Volver al Login
        </a>

    </div>

</div>

<style>

.circle{
    animation: circleDraw 0.6s forwards;
}

.check{
    animation: checkDraw 0.4s 0.6s forwards;
}

@keyframes circleDraw{
    to{
        stroke-dashoffset:0;
    }
}

@keyframes checkDraw{
    to{
        stroke-dashoffset:0;
    }
}

</style>

</x-guest-layout>