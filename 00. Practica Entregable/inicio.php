<?php
    session_start();
    $rolPermitido = 0;
    require_once 'seguridad.php';
    require_once 'conexion.php';
    require_once 'funciones.php';

    $_SESSION['configuracion'] = comprobarConfiguracion($pdo);

    // Creamos las variables
    $error = '';
    $emojisDisponibles = emojisDisponibles($_SESSION['configuracion']['num_emojis']);
    $maxLote = (int)$_SESSION['configuracion']['max_lote'];
    $maxContactos = (int)$_SESSION['configuracion']['max_contactos'];

    // Calculamos cuántos contactos tiene ya y cuántos huecos le quedan
    $contGuardados = contarContactosUsuario($pdo, (int)$_SESSION['codigo']);
    $contDisponibles = max(0, $maxContactos - $contGuardados);
    $maxCont = min($maxLote, $contDisponibles);

    // Si le quedan menos de 3 huecos no puede grabar más
    if ($maxCont < 3) {
        $error = "No puedes grabar más contactos (te quedan $contDisponibles hueco/s, el mínimo por lote es 3).";
        $_SESSION['emojis'] = [];
        $_SESSION['num'] = 0;

    } else {
        // Inicializa en 3 emojis y mínimo de 3 contactos a agregar
        if (!isset($_SESSION['emojis']) || count($_SESSION['emojis']) === 0) {
            $_SESSION['emojis'] = [];
            for ($i = 0; $i < 3; $i++) {
                $_SESSION['emojis'][] = emojiAleatorio($emojisDisponibles);
            }
            $_SESSION['num'] = 3;
        }

        // INCREMENTAR
        if (isset($_POST['incrementar'])) {
            if ($_SESSION['num'] < $maxCont) {
                $_SESSION['emojis'][] = emojiAleatorio($emojisDisponibles);
                $_SESSION['num'] += 1;
            }

            // Si al incrementar llega al tope, envia a agenda.php
            if ($_SESSION['num'] >= $maxCont) {
                header("Location: agenda.php");
                exit;
            }
        }

        // Validaciones de GRABAR
        if (isset($_POST['grabar'])) {
            if ($_SESSION['num'] < 3 || $_SESSION['num'] > $maxCont) {
                $error = "Error. Debes grabar entre 3 y $maxCont contactos.";
            } else {
                header("Location: agenda.php");
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contactos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="caja">
        <h2>AGENDA DE CONTACTOS</h2>

        <form method="POST" action="inicio.php">
            <?php if ($maxCont >= 3): ?>
                <p>
                    Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?></strong> 
                    &middot; 
                    Contactos a grabar: <?php echo $_SESSION['num']; ?>
                </p>

                <div class="emojis-lista">
                    <?php echo mostrarEmojis($_SESSION['emojis']); ?>
                </div>

                <input type="submit" name="incrementar" value="INCREMENTAR">
                <input type="submit" name="grabar" value="GRABAR">
            <?php else: ?>
                <p>Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?></strong></p>
                <p><a href="totales.php">Ver totales</a> | <a href="salir.php">Cerrar sesión</a></p>
            <?php endif; ?>
        </form>

        <!-- Mostramos los errores-->
        <?php if ($error !== ''): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
