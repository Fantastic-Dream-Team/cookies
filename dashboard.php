<?php
session_start();
 
// Protección: si no hay sesión, al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
 
// Contador de visitas (se incrementa cada vez que se carga la página)
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;
 
$nombre  = htmlspecialchars($_SESSION['nombre']);
$rol     = htmlspecialchars($_SESSION['rol']);
$inicio  = htmlspecialchars($_SESSION['inicio']);
$visitas = (int)$_SESSION['visitas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="css/tema-claro.css">
</head>
<body>
<div class="contenedor">
    <nav>
      <a href="index.php">Inicio</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
    <h1>Bienvenido, <?php echo $nombre; ?>!</h1>
    <p class="stat">Rol: <strong><?php echo $rol; ?></strong></p>
    <p class="stat">Sesión iniciada: <?php echo $inicio; ?></p>
    <p class="stat">Visitas a este panel: <strong><?php echo $visitas; ?></strong></p>
  </div>
</body>
</html>
