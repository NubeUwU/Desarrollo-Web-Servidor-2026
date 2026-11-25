CREATE DATABASE IF NOT EXISTS blackjack;
USE blackjack;

CREATE TABLE IF NOT EXISTS jugador (
    login VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    clave VARCHAR(100) NOT NULL,
    puntos INT DEFAULT 0,
    extra INT DEFAULT 0
);

INSERT INTO jugador (login, nombre, clave, puntos, extra) VALUES
('ana', 'Ana García', '1234', 0, 0),
('luis', 'Luis Pérez', '1234', 0, 0);
