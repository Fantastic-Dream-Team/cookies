<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php'); exit;
}
require_once 'db/conexion.php';
$msg = '';
 
// CREAR usuario
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['crear'])) {
    $n  = htmlspecialchars(trim($_POST['nombre']   ?? ''));
    $u  = htmlspecialchars(trim($_POST['usuario']  ?? ''));
    $p  = trim($_POST['password'] ?? '');
    $r  = in_array($_POST['rol']??'',['admin','usuario']) ? $_POST['rol'] : 'usuario';
    if ($n && $u && $p) {
        $hash = hash('sha256', $p);
        $stmt = $conn->prepare('INSERT INTO usuarios (nombre,usuario,password,rol) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss',$n,$u,$hash,$r);
        $stmt->execute() ? $msg='Usuario creado.' : $msg='Error: '.$conn->error;
        $stmt->close();
    } else { $msg = 'Completa todos los campos.'; }
}
 
// ELIMINAR usuario
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['eliminar'])) {
    $id = (int)$_POST['id'];
    if ($id !== (int)$_SESSION['usuario_id']) {
        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();
        $msg = 'Usuario eliminado.';
    } else { $msg = 'No puedes eliminarte a ti mismo.'; }
}
 
// LEER todos los usuarios
$usuarios = $conn->query('SELECT id, nombre, usuario, rol, creado_en FROM usuarios');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Admin Usuarios</title>
  <link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="css/tema-claro.css">
</head>
<body><div class="contenedor">
  <h1>Panel de usuarios</h1>
  <?php if ($msg): ?><p class="msg-ok"><?php echo htmlspecialchars($msg);?></p><?php endif;?>
 
  <h2>Agregar usuario</h2>
  <form method="POST">
    <label>Nombre</label><input type="text" name="nombre">
    <label>Usuario</label><input type="text" name="usuario">
    <label>Contraseña</label><input type="password" name="password">
    <label>Rol</label>
    <select name="rol"><option value="usuario">Usuario</option><option value="admin">Admin</option></select>
    <button type="submit" name="crear">Crear usuario</button>
  </form>
 
  <h2>Usuarios registrados</h2>
  <table border="1" cellpadding="6" style="width:100%;border-collapse:collapse">
    <tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Creado</th><th>Acción</th></tr>
    <?php while($u=$usuarios->fetch_assoc()): ?>
    <tr>
      <td><?php echo $u['id'];?></td>
      <td><?php echo htmlspecialchars($u['nombre']);?></td>
      <td><?php echo htmlspecialchars($u['usuario']);?></td>
      <td><?php echo htmlspecialchars($u['rol']);?></td>
      <td><?php echo htmlspecialchars($u['creado_en']);?></td>
      <td>
        <form method="POST" style="display:inline">
          <input type="hidden" name="id" value="<?php echo $u['id'];?>">
          <button type="submit" name="eliminar">Eliminar</button>
        </form>
      </td>
    </tr>
    <?php endwhile;?>
  </table>
  <br><a href="dashboard.php">← Volver al dashboard</a>
</div></body></html>
