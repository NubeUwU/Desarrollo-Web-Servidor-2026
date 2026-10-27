SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS AGENDA;

USE AGENDA;

CREATE TABLE usuarios(
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    rol TINYINT NOT NULL
);

CREATE TABLE configuracion(
    max_lote INT NOT NULL CHECK (max_lote BETWEEN 3 AND 8),
    num_emojis INT NOT NULL CHECK (num_emojis BETWEEN 4 AND 10),
    max_contactos INT NOT NULL CHECK (max_contactos BETWEEN 10 AND 100)
);

CREATE TABLE contactos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30),
    apellidos VARCHAR(50),
    telefono CHAR(9),
    email VARCHAR(80),
    emoji VARCHAR(8),
    fecha DATETIME,
    codigousu INT,
    FOREIGN KEY (codigousu) REFERENCES usuarios(codigo)
);

-- Insertamos unos usuarios con unos valores y contraseñas en HASH
INSERT INTO usuarios(nombre, clave, rol) VALUES
('admin', '$2y$10$mq1dQkcWidDUsbkCtMOoPe1UjfiTvjMGNdJ5KDY/8S5BCoNcnuj6K' , 1), -- Admin123*
('marcos', '$2y$10$ljBO4fiGCMlbYWPebKfdWu0C4B0b3tcEo3r74jqusBj4MOR5mY2MC' , 0 ), -- ABC1234*
('yolanda', '$2y$10$WWbm/75eaUjsPL1vc8bDdevb6KA5txMg2miVtBjJwHlfe3KIhxgYS' , 0); -- Yolandais2026*