<?php
session_start();
$mensaje = '';

// Procesar el formulario cuando se envía mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lógica para Borrar Preferencias
    if (isset($_POST['borrar'])) {
        foreach (['tema', 'idioma', 'nombre_visitante'] as $c) {
            // Se eliminan estableciendo una fecha en el pasado
            setcookie($c, '', time() - 3600, '/', '', false, true);
        }
        $mensaje = 'Preferencias eliminadas.';

    // Lógica para Guardar Preferencias
    } elseif (isset($_POST['guardar'])) {
        // Validaciones de seguridad
        $tema   = in_array($_POST['tema'] ?? '', ['claro', 'oscuro']) ? $_POST['tema'] : 'claro';
        $idioma = in_array($_POST['idioma'] ?? '', ['es', 'en'])      ? $_POST['idioma'] : 'es';
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? 'Visitante'));

        // Expiración en 30 días (30 días * 24h * 60m * 60s)
        $expira = time() + (30 * 24 * 60 * 60);

        // Creación de cookies con httponly = true para mayor seguridad
        setcookie('tema',             $tema,   $expira, '/', '', false, true);
        setcookie('idioma',           $idioma, $expira, '/', '', false, true);
        setcookie('nombre_visitante', $nombre, $expira, '/', '', false, true);
        
        $mensaje = 'Preferencias guardadas por 30 días.';
    }
}

// Leer valores actuales de las cookies para mostrar en el formulario
$tema_actual   = $_COOKIE['tema']   ?? 'claro';
$idioma_actual = $_COOKIE['idioma'] ?? 'es';
$nombre_actual = isset($_COOKIE['nombre_visitante'])
        ? htmlspecialchars($_COOKIE['nombre_visitante']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Preferencias</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/tema-<?php echo htmlspecialchars($tema_actual); ?>.css">
</head>
<body>
    <div class="contenedor">
        <h1>Preferencias</h1>
        
        <?php if ($mensaje): ?>
            <p class="msg-ok"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form method="POST" action="preferencias.php">
            <label>Tema visual</label>
            <select name="tema">
                <option value="claro"  <?php echo $tema_actual === 'claro'  ? 'selected' : ''; ?>>Claro</option>
                <option value="oscuro" <?php echo $tema_actual === 'oscuro' ? 'selected' : ''; ?>>Oscuro</option>
            </select>

            <label>Idioma</label>
            <select name="idioma">
                <option value="es" <?php echo $idioma_actual === 'es' ? 'selected' : ''; ?>>Español</option>
                <option value="en" <?php echo $idioma_actual === 'en' ? 'selected' : ''; ?>>English</option>
            </select>

            <label>Tu nombre</label>
            <input type="text" name="nombre" value="<?php echo $nombre_actual; ?>">

            <button type="submit" name="guardar">Guardar preferencias</button>
            <button type="submit" name="borrar" style="background-color: #c0392b;">Borrar preferencias</button>
        </form>
        
        <br>
        [cite_start]<a href="index.php">← Volver al inicio</a> [cite: 231]
    </div>
</body>
</html>