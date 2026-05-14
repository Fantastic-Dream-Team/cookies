<?php
session_start();
$tema   = isset($_COOKIE['tema'])            ? $_COOKIE['tema']   : 'claro';
$idioma = isset($_COOKIE['idioma'])          ? $_COOKIE['idioma'] : 'es';
$nombre = isset($_COOKIE['nombre_visitante'])
          ? htmlspecialchars($_COOKIE['nombre_visitante']) : 'Visitante';
 
$textos = [
  'es' => ['bienvenida'=>'Bienvenido','pref'=>'Preferencias','login'=>'Iniciar sesión'],
  'en' => ['bienvenida'=>'Welcome',   'pref'=>'Preferences', 'login'=>'Log in'],
];
$t = $textos[$idioma] ?? $textos['es'];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($idioma); ?>">
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
  <link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="css/tema-<?php echo htmlspecialchars($tema); ?>.css">
</head>
<body>
  <div class="contenedor">
    <nav>
      <a href="preferencias.php"><?php echo $t['pref']; ?></a>
      <a href="login.php"><?php echo $t['login']; ?></a>
    </nav>
    <h1><?php echo $t['bienvenida'] . ', ' . $nombre; ?>!</h1>
    <p>Tema activo: <?php echo htmlspecialchars($tema); ?></p>
    <p>Idioma: <?php echo htmlspecialchars($idioma); ?></p>
  </div>
</body>
</html>
