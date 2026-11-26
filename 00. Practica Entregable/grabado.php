<?php
    session_start();
    $rolPermitido = 0;
    require_once 'seguridad.php';
    require_once 'conexion.php';
    require_once 'funciones.php';

    // Creamos las variables
    $codigoUsuario = (int)$_SESSION['codigo'];
    $nombreUsuario = $_SESSION['usuario'] ?? '';
    $error = '';
    $grabadosEnLote = 0;
    $emojisLote = [];

    // Se inserta el lote si existe en la sesión
    if (!empty($_SESSION['lote_grabar'])) {
        $lote = $_SESSION['lote_grabar'];

        // Se actualizan la base de datos
        if (actualizarContactosUsuario($pdo, $codigoUsuario, $lote)) {
            $grabadosEnLote = count($lote);
            $emojisLote = array_column($lote, 'emoji');

            // Se guardan los datos grabados
            $_SESSION['ultimo_lote_count'] = $grabadosEnLote;
            $_SESSION['ultimo_lote_emojis'] = $emojisLote;

            // Se borran los datos del lote de la sesión 
            unset($_SESSION['lote_grabar'], $_SESSION['emojis'], $_SESSION['num']);
        } else {
            $error = "Error al grabar los contactos en la base de datos.";
        }
    } elseif (isset($_SESSION['ultimo_lote_count'])) {
        $grabadosEnLote = $_SESSION['ultimo_lote_count'];
        $emojisLote = $_SESSION['ultimo_lote_emojis'] ?? [];
    } else {
        header("Location: inicio.php");
        exit;
    }

    // Consultamos el total de contactos del usuario
    $totalAgenda = contarContactosUsuario($pdo, $codigoUsuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Contactos Grabados</title>
</head>
<body>
    <div class="caja">
        <p>Usuario: <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong></p>

        <?php if ($error !== ''): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <p>
                Se han grabado <strong><?php echo $grabadosEnLote; ?></strong> contactos en tu agenda:
                <span class="emojis-inline"><?php echo mostrarEmojis($emojisLote); ?></span>
            </p>
            <p>Total de contactos en tu agenda: <strong><?php echo $totalAgenda; ?></strong></p>
        <?php endif; ?>

        <nav>
            <a href="inicio.php">Grabar más contactos</a> |
            <a href="totales.php">Ver totales</a> |
            <a href="salir.php">Cerrar sesión</a>
        </nav>
    </div>
</body>
</html>
