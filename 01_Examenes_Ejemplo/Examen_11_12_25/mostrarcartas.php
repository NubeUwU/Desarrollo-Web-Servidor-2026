<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// --- Inicializar y barajar cartas si no hay partida activa ---
if (!isset($_SESSION['cartas']) || empty($_SESSION['cartas'])) {
    $imagenes = ["copas_02.jpg", "copas_03.jpg", "copas_05.jpg"];
    $cartas = [];
    foreach ($imagenes as $img) {
        $cartas[] = $img;
        $cartas[] = $img;
    }
    shuffle($cartas);

    $_SESSION['cartas'] = $cartas;
    $_SESSION['visible'] = -1;
    $_SESSION['volteos'] = 0;
}

// --- Al pulsar un botón de carta ---
if (isset($_POST['carta'])) {
    $indice = (int)$_POST['carta'];

    if (isset($_SESSION['cartas'][$indice]) && $_SESSION['visible'] !== $indice) {
        $_SESSION['visible'] = $indice;
        $_SESSION['volteos']++;
    }

    header("Location: mostrarcartas.php");
    exit;
}

// --- Al pulsar comprobar pareja ---
if (isset($_POST['comprobar'], $_POST['pareja1'], $_POST['pareja2'])) {
    $p1 = (int)$_POST['pareja1'] - 1;
    $p2 = (int)$_POST['pareja2'] - 1;

    $_SESSION['pareja1'] = $p1 + 1;
    $_SESSION['pareja2'] = $p2 + 1;

    // Validar índices
    if ($p1 === $p2 || !isset($_SESSION['cartas'][$p1]) || !isset($_SESSION['cartas'][$p2])) {
        $_SESSION['acierto'] = false;
    } else {
        $_SESSION['acierto'] = ($_SESSION['cartas'][$p1] === $_SESSION['cartas'][$p2]);
    }

    // CLAVE DEL PROBLEMA: Borramos las cartas de la sesión para que al volver a jugar se barajen de nuevo
    unset($_SESSION['cartas'], $_SESSION['visible']);

    header("Location: resultado.php");
    exit;
}

// --- Botones ---
$botones = [
    0 => 'Levantar Carta 1',
    1 => 'Levantar Carta 2',
    2 => 'Levantar Carta 3',
    3 => 'Levantar Carta 4',
    4 => 'Levantar Carta 5',
    5 => 'Levantar Carta 6'
];

$volteos = $_SESSION['volteos'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego de Cartas</title>
</head>
<body>

<h1>Bienvenid@, <?php echo htmlspecialchars($_SESSION['login']); ?></h1>

<h2>Cartas Levantadas:
    <input type="number" value="<?php echo (int)$volteos; ?>" readonly>
</h2>
<br>

<!-- Formulario de botones para levantar carta -->
<form method="POST">
<?php foreach ($botones as $valor => $texto): ?>
    <button type="submit" name="carta" value="<?= $valor ?>"><?= $texto ?></button>
<?php endforeach; ?>
</form>

<br><br>

<!-- Formulario para comprobar pareja -->
<form method="POST">
    <h3>Pareja:
        <input type="number" name="pareja1" min="1" max="6" required>
        <input type="number" name="pareja2" min="1" max="6" required>
        <input type="submit" name="comprobar" value="Comprobar">
    </h3>
</form>

<br><br>

<!-- Mostrar cartas -->
<?php
foreach ($_SESSION['cartas'] as $i => $img) {
    $src = ($_SESSION['visible'] === $i) ? "img/$img" : "img/boca_abajo.jpg";
    echo "<img src='" . htmlspecialchars($src) . "' width='175' height='250' style='margin:5px' alt='Carta'>";
}
?>

</body>
</html>
