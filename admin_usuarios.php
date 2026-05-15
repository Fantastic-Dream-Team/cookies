<?php
session_start();
// Seguridad: Solo admin puede entrar
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php'); 
    exit;
}
require_once 'db/conexion.php';
$msg = '';

// CREAR usuario
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['crear'])) {
    $n = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $u = htmlspecialchars(trim($_POST['usuario'] ?? ''));
    $p = trim($_POST['password'] ?? '');
    $r = in_array($_POST['rol']??'', ['admin', 'usuario']) ? $_POST['rol'] : 'usuario';
    
    if ($n && $u && $p) {
        $hash = hash('sha256', $p);
        $stmt = $conn->prepare('INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $n, $u, $hash, $r);
        $stmt->execute() ? $msg='Usuario creado correctamente.' : $msg='Error: '.$conn->error;
        $stmt->close();
    }
}

// ELIMINAR usuario
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['eliminar'])) {
    $id = (int)$_POST['id'];
    if ($id !== (int)$_SESSION['usuario_id']) {
        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute() ? $msg='Usuario eliminado.' : $msg='Error al eliminar.';
        $stmt->close();
    } else {
        $msg = 'No puedes eliminar tu propia cuenta.';
    }
}

$usuarios = $conn->query('SELECT id, nombre, usuario, rol, creado_en FROM usuarios');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Usuarios</h1>
            <a href="dashboard.php" class="btn btn-outline-secondary">Volver al Dashboard</a>
        </div>

        <?php if ($msg): ?>
            <div class="alert alert-info"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Agregar Nuevo</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="usuario" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rol</label>
                                <select name="rol" class="form-select">
                                    <option value="usuario">Usuario Estándar</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <button type="submit" name="crear" class="btn btn-primary w-100">Crear Usuario</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm text-dark">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Rol</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($u = $usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                        <td><code>@<?php echo htmlspecialchars($u['usuario']); ?></code></td>
                                        <td><span class="badge <?php echo $u['rol']==='admin' ? 'bg-danger':'bg-success'; ?>">
                                            <?php echo strtoupper($u['rol']); ?></span>
                                        </td>
                                        <td>
                                            <form method="POST" onsubmit="return confirm('¿Eliminar usuario?');">
                                                <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" name="eliminar" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>