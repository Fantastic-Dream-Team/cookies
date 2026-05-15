<?php
// Configurar zona horaria de Panamá
date_default_timezone_set('America/Panama');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Usuario por defecto de XAMPP
define('DB_PASS', '');          // Sin contraseña en XAMPP local
define('DB_NAME', 'taller_cookies');
define('DB_PORT', 3307);        // Definicion del puerto

// Se agrega el quinto parámetro para especificar el puerto
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    // Si falla por puerto, lanzará un error detallado aquí
    die('Error de conexión (Puerto '.DB_PORT.'): ' . htmlspecialchars($conn->connect_error));
}

$conn->set_charset('utf8mb4');
?>