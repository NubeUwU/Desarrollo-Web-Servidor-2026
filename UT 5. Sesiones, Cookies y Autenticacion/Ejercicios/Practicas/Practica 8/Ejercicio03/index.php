<?php
session_start();
$posicionX = $_SESSION['posicionX'] ?? 0;
$posicionY = $_SESSION['posicionY'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['action'] ?? '';

    $posicionX = match ($accion) {
        'izquierda' => $posicionX - 20,
        'derecha'   => $posicionX + 20,
        'reset'     => 0,
        default     => $posicionX
    };

    $posicionY = match ($accion) {
        'arriba' => $posicionY - 20,
        'abajo'  => $posicionY + 20,
        'reset'  => 0,
        default  => $posicionY
    };

    // Limitar el rango de las posiciones a -300 y 300
    $posicionX = match (true) {
        $posicionX < -300 => 300,
        $posicionX > 300 => -300,
        default => $posicionX
    };

    $posicionY = match (true) {
        $posicionY < -300 => 300,
        $posicionY > 300 => -300,
        default => $posicionY
    };

    // Guardamos las posiciones en la sesión
    $_SESSION['posicionX'] = $posicionX;
    $_SESSION['posicionY'] = $posicionY;

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>

    <div class="contenedor">
        <form action="index.php" method="post">
            <div class="contenido">
                <div class="botones">

                <!-- Botones de control -->
                    <button class="arriba" type="submit" name="action" value="arriba">
                        <img src="img/arriba.png">
                    </button>

                    <button class="izquierda" type="submit" name="action" value="izquierda">
                        <img src="img/izquierda.png">
                    </button>

                    <button class="centro" type="submit" name="action" value="reset">
                        Volver al centro
                    </button>

                    <button class="derecha" type="submit" name="action" value="derecha">
                        <img src="img/derecha.png">
                    </button>

                    <button class="abajo" type="submit" name="action" value="abajo">
                        <img src="img/abajo.png">
                    </button>

                </div>

                <div class="mapa">

                    <svg width="616" height="616" viewBox="-308 -308 616 616">

                        <!-- Punto -->
                        <circle cx="<?php echo $posicionX; ?>"
                                cy="<?php echo $posicionY; ?>"
                                r="8" fill="red"/>

                    </svg>

                    <p>
                        <?php echo "Posición X: " . $posicionX . " | Posición Y: " . $posicionY; ?>
                    </p>

                </div>

            </div>

        </form>

    </div>

</body>
</html>
