<?php
    session_start();
    
    // Comprobacion de seguridad
    $rolPermitido = 0;
    require_once 'seguridad.php';

    require_once 'conexion.php';
    require_once 'funciones.php';

    // Si entra sin emojis en sesión, volver a inicio.php
    if (empty($_SESSION['emojis'])) {
        header('Location: inicio.php');
        exit;
    }

    // 1. Primero inicializamos las variables
    $valores = $_POST['contactos'] ?? [];
    $errores = [];

    // 2. Después comprobamos si se ha pulsado el botón 'grabar'
    if (isset($_POST['grabar'])) {
        foreach ($_SESSION['emojis'] as $i => $emoji) {
            $nom = trim($valores[$i]['nombre'] ?? '');
            $ape = trim($valores[$i]['apellidos'] ?? '');
            $tel = trim($valores[$i]['telefono'] ?? '');
            $ema = trim($valores[$i]['email'] ?? '');

            if ($nom === '') {
                $errores[$i]['nombre'] = 'Obligatorio';
            }
            if ($ape === '') {
                $errores[$i]['apellidos'] = 'Obligatorio';
            }
            if ($tel === '' || !preg_match('/^[0-9]{9}$/', $tel)) {
                $errores[$i]['telefono'] = 'Teléfono no válido';
            }
            if ($ema === '' || !filter_var($ema, FILTER_VALIDATE_EMAIL)) {
                $errores[$i]['email'] = 'Email no válido';
            }
        }

        // 3. Si no hay errores, se guarda el lote de contactos en sesión y pasamos a grabado.php
        if (empty($errores)) {
            $_SESSION['lote_grabar'] = [];
            foreach ($_SESSION['emojis'] as $i => $emoji) {
                $_SESSION['lote_grabar'][] = [
                    'emoji'     => $emoji,
                    'nombre'    => trim($valores[$i]['nombre']),
                    'apellidos' => trim($valores[$i]['apellidos']),
                    'telefono'  => trim($valores[$i]['telefono']),
                    'email'     => trim($valores[$i]['email'])
                ];
            }

            header("Location: grabado.php");
            exit;
        }

    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <p>Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario'] ?? ''); ?></strong></p>

    <form method="POST" action="agenda.php">
        <div class="grid-contactos">
            <?php
                foreach ($_SESSION['emojis'] as $i => $emoji) {
                    echo pintarFormularioContacto(
                        $i,
                        $emoji,
                        $valores[$i] ?? [],
                        $errores[$i] ?? []
                    );
                }
            ?>
        </div>
        <br>
        <input type="submit" name="grabar" value="Grabar contactos">
    </form>

</body>
</html>
