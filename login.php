<?php
session_start();

// Si ya hay sesión activa, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'db/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario === '' || $password === '') {
        $error = 'Por favor completa todos los campos.';
    } else {
        // Preparar consulta segura con prepared statement
        $stmt = $conn->prepare('SELECT id, nombre, rol, password FROM usuarios WHERE usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();
            $hash = hash('sha256', $password);

            if (hash_equals($fila['password'], $hash)) {
                // Regenerar ID de sesión para seguridad
                session_regenerate_id(true);

                // Guardar datos en la sesión
                $_SESSION['usuario_id'] = $fila['id'];
                $_SESSION['nombre'] = $fila['nombre'];
                $_SESSION['rol'] = $fila['rol'];
                $_SESSION['inicio'] = date('d/m/Y H:i:s');
                $_SESSION['visitas'] = 0;

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/tema-claro.css">
</head>
<body>
    <div class="contenedor">
        <h1>Iniciar sesión</h1>

        <?php if ($error): ?>
            <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>Usuario</label>
            <input type="text" name="usuario" autocomplete="username">

            <label>Contraseña</label>
            <input type="password" name="password" autocomplete="current-password">

            <button type="submit">Entrar</button>
        </form>

        <br><a href="index.php">← Volver al inicio</a>
    </div>
</body>
</html>