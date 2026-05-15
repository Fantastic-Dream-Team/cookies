<?php
session_start();
if (isset($_SESSION['usuario_id'])) { header('Location: dashboard.php'); exit; }
require_once 'db/conexion.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['usuario'] ?? '');
    $p = trim($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT id, nombre, rol, password FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $f = $res->fetch_assoc();
        if (hash_equals($f['password'], hash('sha256', $p))) {
            // Al validar las credenciales con éxito:
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $f['id'];
            $_SESSION['nombre'] = $f['nombre'];
            $_SESSION['rol'] = $f['rol'];

            // Esto ahora marcará la hora exacta de Panamá (formato 12h con AM/PM)
            $_SESSION['inicio'] = date('g:i:s A'); 
            $_SESSION['visitas'] = 0;
        }
    }
    $error = 'Credenciales inválidas.';
}
$tema = $_COOKIE['tema'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tema<?php echo $tema; ?>.css">
</head>
<body>
    <div class="container contenedor-login">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Acceso</h3>
                <?php if($error): ?> <div class="alert alert-danger p-2 small"><?php echo $error; ?></div> <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>