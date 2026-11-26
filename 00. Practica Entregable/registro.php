<?php
session_start();

require_once 'funciones.php';
$pdo = require 'conexion.php';

$errores = [];
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Creamos las variables del formulario
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $accion = $_POST['accion'] ?? '';

    // Si pulsa "Volver", regresar a index.php
    if ($accion === 'volver') {
        header("Location: index.php");
        exit;
    }

    // Validaciones de los campos
    if ($usuario === '') {
        $errores['usuario'] = '<p class="error">El nombre de usuario no puede estar vacío.</p>';
    } elseif (mb_strlen($usuario, 'UTF-8') > 20 || !preg_match('/^[A-Za-z0-9_.@ñÑ-]+$/u', $usuario)) {
        $errores['usuario'] = '<p class="error">Formato de usuario no válido.</p>';
    }

    if ($password === '') {
        $errores['password'] = '<p class="error">La contraseña no puede estar vacía.</p>';
    }

    // Si los campos son válidos, se registra el usuario
    if (empty($errores) && $accion === 'registrar') {
        $nuevoCodigo = registrarUsuario($pdo, $usuario, $password);

        if ($nuevoCodigo !== false) {

            // Iniciamos sesión con rol 0
            session_regenerate_id(true);
            $_SESSION['codigo'] = $nuevoCodigo;
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = 0;

            unset(
                $_SESSION['emojis'],
                $_SESSION['num'],
                $_SESSION['lote_grabar'],
                $_SESSION['ultimo_lote_count'],
                $_SESSION['ultimo_lote_emojis']
            );

            header("Location: inicio.php");
            exit;
        } else {
            $errores['usuario'] = '<p class="error">Ese nombre de usuario ya está registrado.</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class="caja">

        <h1>REGISTRO DE USUARIO</h1>

        <form method="POST" action="registro.php">

            <div class="campo-login">
                <label for="usuario">USUARIO:</label>
                <input type="text" id="usuario" name="usuario"
                       value="<?php echo htmlspecialchars($usuario); ?>"
                       maxlength="20"
                       autocomplete="off"
                       required>
            </div>

            <!-- Errores de usuario -->
            <?php echo $errores['usuario'] ?? ''; ?>

            <div class="campo-login">
                <label for="password">CLAVE:</label>
                <input type="password" id="password" name="password"
                       autocomplete="off" required>
            </div>

            <!-- Errores de contraseña -->
            <?php echo $errores['password'] ?? ''; ?>

            <div class="botones-login">
                <button type="submit" name="accion" value="registrar">Crear usuario</button>
                <button type="submit" name="accion" value="volver" formnovalidate>Volver</button>
            </div>

        </form>
    </div>
</body>
</html>
