<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Optimización Corporativa</title>

    <!-- Bootstrap CSS (para que se vea bonito) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilo personalizado -->
    <style>
        body {
            background: linear-gradient(135deg, #fafbff, #ffffff, #ecf5f8);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            max-width: 500px;
        }
        .btn-login {
            background: #007bff;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
        }
        .btn-login:hover {
            background: #0056b3;
        }
        .logo {
            max-width: 180px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container text-center">
        <div class="card p-5">
            <!-- Tu logo -->
            <img src="{{ asset('img/Recurso 18@3x.png') }}" alt="Logo" class="logo mx-auto d-block">

            <h1 class="mb-4 fw-bold">Bienvenido a</h1>
            <h2 class="mb-5">Optimización Corporativa</h2>

            <p class="lead mb-5">Control de inventario • Gestión de usuarios</p>

            <div class="d-grid gap-3">
                <a href="{{ route('login') }}" class="btn btn-primary btn-login btn-lg">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (opcional, para interacciones) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>