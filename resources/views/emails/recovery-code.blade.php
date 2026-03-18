<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperación de contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .code-box { background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 28px; font-weight: bold; color: #2c3e50; margin: 20px 0; }
    </style>
</head>
<body>
    <h2>Holaaaaaaaaaa :D!</h2>
    <p>Has solicitado recuperar tu contraseña. Usa el siguiente código para continuar:</p>
    <div class="code-box">{{ $code }}</div>
    <p>Este código expirará en <strong>1 hora D:</strong>.</p>
    <p>Si no solicitaste este cambio, ignora este correo.</p>
    <p> Este correo es generado por el sistema de inventario favor de no responder</p>
</body>
</html>