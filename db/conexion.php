<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Usuario por defecto de XAMPP
define('DB_PASS', '');          // Sin contraseña en XAMPP local
define('DB_NAME', 'taller_cookies');
 
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 
if ($conn->connect_error) {
    die('Error de conexión: ' . htmlspecialchars($conn->connect_error));
}
 
$conn->set_charset('utf8mb4');
?>
