<?php
session_start();

// Comprobación de seguridad
$rolPermitido = 1;
require_once 'seguridad.php';
require_once 'funciones.php';
$pdo = require 'conexion.php';

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar'])) {
        $maxLote = filter_input(INPUT_POST, 'max_lote', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 3, 'max_range' => 8]
        ]);
        $numEmojis = filter_input(INPUT_POST, 'num_emojis', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 4, 'max_range' => 10]
        ]);
        $maxContactos = filter_input(INPUT_POST, 'max_contactos', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 10, 'max_range' => 100]
        ]);

        if ($maxLote === false) {
            $errores[] = "Contactos por lote debe estar entre 3 y 8.";
        }
        if ($numEmojis === false) {
            $errores[] = "Emojis disponibles debe estar entre 4 y 10.";
        }
        if ($maxContactos === false) {
            $errores[] = "Máx. contactos por usuario debe estar entre 10 y 100.";
        }

        if (empty($errores)) {
            actualizarConfiguracion($pdo, $maxLote, $numEmojis, $maxContactos);
            $mensaje = "Configuración guardada correctamente.";
        }
    }
}

// Se lee la configuración actual y los datos de los usuarios
$config = comprobarConfiguracion($pdo);
$usuarios = obtenerDatosUsuarios($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="caja">
        <h1>Panel de Administración</h1>
        <p>
            Conectado como: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong> (rol <?php echo (int)$_SESSION['rol']; ?>) 
            | <a href="salir.php">Cerrar sesión</a>
        </p>

        <form method="POST" action="admin.php" class="form-admin">
            <div class="campo-admin">
                <label for="max_lote">Contactos por lote (3–8):</label>
                <input type="number" id="max_lote" name="max_lote" min="3" max="8" required>
                <span><?php echo $config['max_lote']; ?></span>
            </div>

            <div class="campo-admin">
                <label for="num_emojis">Emojis disponibles (4–10):</label>
                <input type="number" id="num_emojis" name="num_emojis" min="4" max="10" required>
                <span><?php echo $config['num_emojis']; ?></span>
            </div>

            <div class="campo-admin">
                <label for="max_contactos">Máx. contactos por usuario (10–100):</label>
                <input type="number" id="max_contactos" name="max_contactos" min="10" max="100" required>
                <span><?php echo $config['max_contactos']; ?></span>
            </div>

            <div class="boton-admin">
                <button type="submit" name="guardar">Guardar configuración</button>
            </div>
        </form>

        <!-- Mensajes de acierto o error -->
        <?php foreach ($errores as $err): ?>
            <p class="error"><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>

        <?php if ($mensaje !== ''): ?>
            <p class="acierto"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <!-- Tabla de datos de los usuarios -->
        <table>
            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Contactos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo (int)$u['codigo']; ?></td>
                        <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                        <td><?php echo (int)$u['rol']; ?></td>
                        <td><?php echo (int)$u['contactos']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
