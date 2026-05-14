CREATE DATABASE IF NOT EXISTS taller_cookies;
USE taller_cookies;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','usuario') DEFAULT 'usuario',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contraseña: 123456 (hasheada con SHA256)
INSERT INTO usuarios (nombre, usuario, password, rol) VALUES
('Ana García', 'ana', SHA2('123456', 256), 'admin'),
('Luis Pérez', 'luis', SHA2('123456', 256), 'usuario'),
('María López', 'maria', SHA2('123456', 256), 'usuario');