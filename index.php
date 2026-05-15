<?php
session_start();
$tema   = $_COOKIE['tema']   ?? 'claro';
$idioma = $_COOKIE['idioma'] ?? 'es';
$nombre = isset($_COOKIE['nombre_visitante']) ? htmlspecialchars($_COOKIE['nombre_visitante']) : 'Visitante';

$textos = [
    'es' => ['bienvenida'=>'Bienvenido', 'pref'=>'Preferencias', 'login'=>'Iniciar Sesión', 'tema'=>'Tema activo'],
    'en' => ['bienvenida'=>'Welcome', 'pref'=>'Preferences', 'login'=>'Log In', 'tema'=>'Active theme'],
];
$t = $textos[$idioma] ?? $textos['es'];
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Taller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tema<?php echo $tema; ?>.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Lab7-PHP</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="preferencias.php"><?php echo $t['pref']; ?></a>
                <a class="nav-link btn btn-primary text-white ms-lg-3" href="login.php"><?php echo $t['login']; ?></a>
            </div>
        </div>
    </nav>

    <div class="container text-center">
        <div class="card p-5 shadow-sm">
            <h1 class="display-4"><?php echo $t['bienvenida'] . ', ' . $nombre; ?>!</h1>
            <p class="lead mt-3"><?php echo $t['tema']; ?>: <span class="badge bg-secondary"><?php echo ucfirst($tema); ?></span></p>
        </div>
    </div>
</body>
</html>