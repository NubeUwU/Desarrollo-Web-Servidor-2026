<?php
// Creamos una sesion
session_start();

// Llamamos al archivo funciones y creamos una conexion
require_once 'funciones.php';
$pdo = require 'conexion.php';

// Creamos las variables
$errores = [];
$usuario = '';

// Si se ha enviado el formulario, se procesan los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $accion = $_POST['accion'] ?? '';

    // Si el usuario presiona "Registrarse" cambia a registro.php
    if ($accion === 'registrarse') {
        header("Location: registro.php");
        exit;
    }

    // Validaciones Usuario
    if (
        $usuario === '' ||
        mb_strlen($usuario, 'UTF-8') > 20 ||
        !preg_match('/^[A-Za-z0-9_.@ñÑ-]+$/u', $usuario)
    ) {
        $errores['usuario'] = '<p class="error">El usuario está vacío o contiene caracteres no permitidos.</p>';
    }

    // Validaciones Contraseña
    if ($password === '') {
        $errores['password'] = '<p class="error">Debes introducir una contraseña.</p>';
    }

    // Si no hay errores, comprueba si el usuario existe
    if (empty($errores) && $accion === 'entrar') {
        $resultado = comprobarUsuario($pdo, $usuario, $password);

        if ($resultado !== false) {
            if ($resultado['rol'] == 1 || $resultado['rol'] == 0) {
                session_regenerate_id(true);

                // Guardamos los datos del usuario en la sesion
                $_SESSION['codigo'] = $resultado['codigo'];
                $_SESSION['usuario'] = $resultado['usuario'];
                $_SESSION['rol'] = $resultado['rol'];

                // Limpiamos posibles datos viejos
                unset($_SESSION['emojis'], $_SESSION['num']);

                // Redirigimos segun el rol
                if ($resultado['rol'] == 1) {
                    header("Location: admin.php");
                    exit;
                } elseif ($resultado['rol'] == 0) {
                    header("Location: inicio.php");
                    exit;
                }
            }
        } else {
            $errores['password'] = '<p class="error">El usuario o la contraseña no existen.</p>';
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
    <title>Agenda</title>
</head>
<body>
    <div class="caja">

        <h1>AGENDA DE CONTACTOS</h1>

        <form method="POST" action="index.php">

            <div class="campo-login">
                <label for="usuario">USUARIO:</label>
                <input type="text" id="usuario" name="usuario"
                       value="<?php echo htmlspecialchars($usuario); ?>"
                       maxlength="20"
                       pattern="[A-Za-z0-9_.@ñÑ-]+"
                       title="Solo letras, números, puntos, arrobas y guiones."
                       autocomplete="off"
                       required>
            </div>

            <!-- Se muestran los errores del usuario -->
            <?php echo $errores['usuario'] ?? ''; ?>

        
            <div class="campo-login">
                <label for="password">CLAVE:</label>
                <input type="password" id="password" name="password"
                       autocomplete="off" required>
            </div>

            <!-- Se muestran los errores de la contraseña -->
            <?php echo $errores['password'] ?? ''; ?>

            <div class="botones-login">
                <button type="submit" name="accion" value="entrar">Entrar</button>
                <button type="submit" name="accion" value="registrarse" formnovalidate>Registrarse</button>
            </div>

        </form>
    </div>
</body>
</html>
