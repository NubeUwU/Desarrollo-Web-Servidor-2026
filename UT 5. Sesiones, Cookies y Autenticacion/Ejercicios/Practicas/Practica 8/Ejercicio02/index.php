
<?php
session_start();
$posicion = $_SESSION['posicion'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posicion = match ($_POST['action'] ?? '') {
        'izquierda' => $posicion - 20,
        'derecha'   => $posicion + 20,
        'reset'     => 0,
        default     => $posicion
    };

    if ($posicion < -300) {
        $posicion = 300;
    } elseif ($posicion > 300) {
        $posicion = -300;
    }

    $_SESSION['posicion'] = $posicion;

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
</head>
<body>
    <form action="index.php" method="post" style="text-align: center;">
        
    <!-- Botones para mover el punto -->
    <div class="botones" style="margin-top: 30px;">
            <button type="submit" name="action" value="izquierda">
                <img src="img/izquierda.png" width="50" height="50">
            </button>
            <button type="submit" name="action" value="derecha">
                <img src="img/derecha.png" width="50" height="50">
            </button>
        </div>
        <br><br>

        <!-- Linea y el Punto -->
        <svg width="616" height="60" viewBox="-308 0 616 60">
            <!-- Línea -->
            <line x1="-300" y1="40" x2="300" y2="40"
                  stroke="black" stroke-width="2"/>

            <!-- Punto -->
            <circle cx="<?php echo $posicion; ?>" cy="40" r="8" fill="red"/>
        </svg>

        <br><br>

    <!-- Botón para volver al centro -->
        <button type="submit" name="action" value="reset">Volver al centro</button>
    </form>

    <?php echo "Posición actual: " . $posicion; ?>

</body>
</html>
