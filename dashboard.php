<?php
session_start();

// Control de acceso: Si no hay sesión, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Incrementar contador de visitas de esta sesión
$_SESSION['visitas']++;

// Leer preferencias de cookies para el diseño
$tema = $_COOKIE['tema'] ?? 'claro';
$nombre_user = $_SESSION['nombre'];
$rol_user = $_SESSION['rol'];
$hora_inicio = $_SESSION['inicio'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tema<?php echo $tema; ?>.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Panel de Control</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link disabled text-muted">Sesión iniciada a las: <?php echo $hora_inicio; ?></span>
                <a class="btn btn-outline-danger btn-sm ms-3" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card p-4 shadow-sm border-0 bg-primary text-white">
                    <h2>¡Hola de nuevo, <?php echo htmlspecialchars($nombre_user); ?>!</h2>
                    <p class="mb-0">Tienes privilegios de: <strong><?php echo strtoupper($rol_user); ?></strong></p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center shadow-sm">
                    <span class="text-muted text-uppercase small fw-bold">Visitas en esta sesión</span>
                    <span class="stat-val text-primary"><?php echo $_SESSION['visitas']; ?></span>
                    <p class="small mt-2 text-muted">Recargas de página detectadas</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 text-center shadow-sm">
                    <span class="text-muted text-uppercase small fw-bold">Preferencias</span>
                    <div class="mt-3">
                        <p>Tema: <span class="badge bg-info text-dark"><?php echo ucfirst($tema); ?></span></p>
                        <a href="preferencias.php" class="btn btn-sm btn-outline-secondary">Cambiar ajustes</a>
                    </div>
                </div>
            </div>

            <?php if ($rol_user === 'admin'): ?>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center shadow-sm border-warning">
                    <span class="text-muted text-uppercase small fw-bold">Panel Administrativo</span>
                    <div class="mt-3">
                        <p class="small">Gestión de base de datos y usuarios</p>
                        <a href="admin_usuarios.php" class="btn btn-warning w-100">Administrar Usuarios</a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center shadow-sm bg-light">
                    <span class="text-muted text-uppercase small fw-bold">Acceso Restringido</span>
                    <p class="mt-3 text-muted small">No tienes permisos para ver las herramientas de administración.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <footer class="mt-5 py-4 text-center text-muted border-top">
            <small>&copy; 2026 Laboratorio de Cookies y Sesiones - UTP</small>
        </footer>
    </div>
</body>
</html>