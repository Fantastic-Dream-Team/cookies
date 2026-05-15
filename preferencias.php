<?php
session_start();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['borrar'])) {
        foreach (['tema', 'idioma', 'nombre_visitante'] as $c) {
            setcookie($c, '', time() - 3600, '/', '', false, true);
        }
        header("Location: preferencias.php?m=1"); exit;
    } elseif (isset($_POST['guardar'])) {
        $tema = in_array($_POST['tema'], ['claro', 'oscuro']) ? $_POST['tema'] : 'claro';
        $idioma = in_array($_POST['idioma'], ['es', 'en']) ? $_POST['idioma'] : 'es';
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? 'Visitante'));
        $expira = time() + (30 * 24 * 60 * 60);
        setcookie('tema', $tema, $expira, '/', '', false, true);
        setcookie('idioma', $idioma, $expira, '/', '', false, true);
        setcookie('nombre_visitante', $nombre, $expira, '/', '', false, true);
        header("Location: preferencias.php?m=2"); exit;
    }
}

if(isset($_GET['m'])) $mensaje = ($_GET['m'] == '1') ? 'Preferencias borradas.' : 'Preferencias guardadas.';
$tema_actual = $_COOKIE['tema'] ?? 'claro';
$idioma_actual = $_COOKIE['idioma'] ?? 'es';
$nombre_actual = $_COOKIE['nombre_visitante'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Preferencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/tema<?php echo $tema_actual; ?>.css">
</head>
<body>
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="card-title mb-4">Configuración</h2>
                <?php if ($mensaje): ?>
                    <div class="alert alert-success"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tema Visual</label>
                        <select name="tema" class="form-select">
                            <option value="claro" <?php if($tema_actual=='claro') echo 'selected';?>>Claro</option>
                            <option value="oscuro" <?php if($tema_actual=='oscuro') echo 'selected';?>>Oscuro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Idioma</label>
                        <select name="idioma" class="form-select">
                            <option value="es" <?php if($idioma_actual=='es') echo 'selected';?>>Español</option>
                            <option value="en" <?php if($idioma_actual=='en') echo 'selected';?>>English</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Tu Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre_actual); ?>">
                    </div>
                    <button type="submit" name="guardar" class="btn btn-primary w-100 mb-2">Guardar</button>
                    <button type="submit" name="borrar" class="btn btn-outline-danger w-100">Borrar Todo</button>
                </form>
                <div class="mt-3 text-center"><a href="index.php" class="text-decoration-none">← Volver</a></div>
            </div>
        </div>
    </div>
</body>
</html>