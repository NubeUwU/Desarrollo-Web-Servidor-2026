<?php
session_start();

// Verificar que esté logueado y sea administrador (rol == 1)
if (empty($_SESSION['usuario']) || ($_SESSION['rol'] ?? 0) != 1) {
    header("Location: index.php");
    exit;
}

require_once 'funciones.php';
$pdo = require 'conexion.php';

$errores = [];
$mensaje = '';

// Procesar el formulario cuando se pulsa Guardar o Salir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['salir'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

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

// Leemos la configuración actual (ya actualizada si se acaba de guardar)
$config = comprobarConfiguracion($pdo);
$usuarios = obtenerDatosUsuarios($pdo);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
</head>
<body>
    <div style="text-align: center;">
        <h1>PANEL DE ADMINISTRACIÓN</h1>
        <p>Conectado como: <b><?php echo htmlspecialchars($_SESSION['usuario']); ?></b> | Rol: <?php echo $_SESSION['rol']; ?></p>

        <?php foreach ($errores as $err): ?>
            <p style="color: red;"><?php echo $err; ?></p>
        <?php endforeach; ?>

        <?php if ($mensaje !== ''): ?>
            <p style="color: green;"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form method="POST" action="admin.php">
            <div>
                <label for="max_lote">Contactos por lote (3 - 8):</label>
                <input type="number" id="max_lote" name="max_lote"
                       min="3" max="8" required>
                <span><?php echo $config['max_lote']; ?></span>

            </div>
                <br>
            <div>
                <label for="num_emojis">Emojis disponibles (4 - 10):</label>
                <input type="number" id="num_emojis" name="num_emojis"
                       min="4" max="10" required>
                <span><?php echo $config['num_emojis']; ?></span>
                
            </div>
                <br>
            <div>
                <label for="max_contactos">Max. contactos por usuario (10 - 100):</label>
                <input type="number" id="max_contactos" name="max_contactos"
                       min="10" max="100" required>
                <span><?php echo $config['max_contactos']; ?></span>
                <br>
            </div>
                <br>

            <div>
                <button type="submit" name="guardar">Guardar</button>
                <button type="submit" name="salir" formnovalidate>Cerrar sesión</button>
            </div>
        </form>
    </div>

    <br><br>

    
    <!-- Tabla de datos de los usuarios-->
    <div> 
        <table border="1" cellpadding="6" cellspacing="0" style="margin: 0 auto;">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Contactos</th>
            </tr>
        </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['codigo']; ?></td>
                    <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                    <td><?php echo $u['rol']; ?></td>
                    <td><?php echo $u['contactos']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
