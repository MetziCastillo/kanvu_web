<!DOCTYPE html>
<html>

<head>
    <title>Bienvenida</title>
</head>

<body>
    <h1>Hola, {{ $user->first_name }} {{ $user->last_name }}</h1>
    <p>Tu cuenta ha sido creada exitosamente.</p>
    <p>Tus credenciales de acceso son:</p>
    <ul>
        <li><strong>Correo:</strong> {{ $user->email }}</li>
        <li><strong>Contraseña temporal:</strong> {{ $password }}</li>
    </ul>
    <p>Por seguridad, te recomendamos cambiar esta contraseña después de iniciar sesión.</p>
    <p>Saludos,<br>El equipo de administración.</p>
</body>

</html>