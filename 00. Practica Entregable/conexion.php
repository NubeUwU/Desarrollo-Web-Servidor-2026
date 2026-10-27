<?php

// Parámetros de conexión
$host = 'localhost';
$dbname = 'agenda';
$usuario = 'usuario_agenda';
$clave = 'ABC123*';

// Cadena DSN
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Opciones de configuración
$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);

    return $pdo;

} catch (PDOException $e) {
    // Guarda el error en el log del servidor, muestra un mensaje por pantalla y detiene la ejecucion
    error_log("Error de conexión PDO: " . $e->getMessage());

    die("No se pudo conectar a la base de datos."); 
}
?>
