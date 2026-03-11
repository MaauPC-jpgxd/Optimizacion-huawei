<x-guest-layout>
    <div class="container">
        <div class="confirmation-box">
            <div class="check-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>¡Contraseña Cambiada!</h1>
            <p>Tu contraseña ha sido actualizada exitosamente.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Volver al Login</a>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #f8f9fa;
            }

            .confirmation-box {
                background-color: #fff;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
                width: 400px;
            }

            .check-icon {
                font-size: 80px;
                color: #28a745; /* Verde */
                margin-bottom: 20px;
                animation: checkAnimation 1s ease-in-out;
            }

            h1 {
                color: #343a40;
                margin-bottom: 15px;
            }

            p {
                color: #6c757d;
                margin-bottom: 25px;
            }

            .btn-primary {
                background-color: #007bff;
                color: #fff;
                padding: 12px 25px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #0056b3;
            }

            @keyframes checkAnimation {
                0% {
                    transform: scale(0);
                }
                100% {
                    transform: scale(1);
                }
            }
        </style>
    @endpush
</x-guest-layout>