<?php
// Creamos una sesion
session_start();

// Llamamos al archivo funciones y creamos una conexion
require_once 'funciones.php';
$pdo = require 'conexion.php';

// Creamos los errores vacios
$errorUsuario = "";
$errorPassword = "";


// Si se ha enviado el formulario, se procesan los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $accion = $_POST['accion'] ?? '';
    $errorUsuario = "";
    $errorPassword = "";


    // Si el usuario presiona "Registrarse", redirigimos de inmediato
    if ($accion === 'registrarse') {
        header("Location: registro.php");
        exit;
    }


    // Validaciones

    // Nombre
    if (
        $usuario === '' ||
        mb_strlen($usuario, 'UTF-8') > 20 ||
        !preg_match('/^[A-Za-z0-9_.@ñÑ-]+$/u', $usuario)
    ) {
        $errorUsuario = '<p style="color: red;">El usuario está vacío o contiene caracteres no permitidos.</p>';
    }

    // Contraseña
    if ($password === '') {
        $errorPassword = '<p style="color: red;">Debes introducir una contraseña.</p>';
    }


    // Si no hay errores, comprueba si el usuario existe
    if ($errorUsuario === '' && $errorPassword === '' && $accion === 'entrar') {
        $resultado = comprobarUsuario($pdo, $usuario, $password);

        if ($resultado !== false) {
            // Regeneramos el id de sesion
            if ($resultado['rol'] == 1 || $resultado['rol'] == 0) {
                session_regenerate_id(true);

                // Guardamos los datos del usuario en la sesion
                $_SESSION['codigo'] = $resultado['codigo'];
                $_SESSION['usuario'] = $resultado['usuario'];
                $_SESSION['rol'] = $resultado['rol'];

                // Redirigimos segun el rol
                if ($resultado['rol'] == 1) {
                    header("Location: admin.php");
                    exit;
                } elseif ($resultado['rol'] == 0) {
                    header("Location: agenda.php");
                    exit;
                }
            }
            
        } else {
            // Si no existe el usuario o la contraseña es incorrecta, muestra un mensaje de error
            $errorPassword = '<p style="color: red;">El usuario o la contraseña no existen.</p>';
        }
    }
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="contenedor">
        <p class="pantalla">PANTALLA: index.php</p>

        <h1>AGENDA DE CONTACTOS</h1>

        <form method="POST" action="index.php">

            <div class="campo">
                <label for="usuario">USUARIO:</label>
                <input type="text" id="usuario" name="usuario"
                       maxlength="20"
                       pattern="[A-Za-z0-9_.@ñÑ-]+"
                       title="Solo letras, números, puntos, arrobas y guiones." autocomplete= "off"
                       required>
            </div>

            <?php echo $errorUsuario; ?>

            <div class="campo">
                <label for="password">CLAVE:</label>
                <input type="password" name="password"
                       autocomplete="off" required>
            </div>

            <?php echo $errorPassword; ?>

            <div class="botones">
                <button type="submit" name="accion" value="entrar">Entrar</button>
                <button type="submit" name="accion" value="registrarse"
                        formnovalidate>Registrarse</button>
            </div>

        </form>
    </div>
</body>
</html>