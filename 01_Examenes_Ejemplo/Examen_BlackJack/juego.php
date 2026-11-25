<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 1. Inicializar mazo (máximo 4 cartas de cada número del 1 al 10)
if (empty($_SESSION['mazo'])) {
    $mazo = [];
    for ($num = 1; $num <= 10; $num++) {
        $mazo[] = $num;
        $mazo[] = $num;
        $mazo[] = $num;
        $mazo[] = $num;
    }
    shuffle($mazo);

    // Repartimos 1 carta al jugador y 1 carta inicial a la banca
    $cartaJugador = array_pop($mazo);
    $cartaBanca = array_pop($mazo);

    $_SESSION['mazo'] = $mazo;
    $_SESSION['mano'] = [$cartaJugador];
    $_SESSION['suma'] = $cartaJugador;
    $_SESSION['cartas_pedidas'] = 1;

    $_SESSION['mano_banca'] = [$cartaBanca];
    $_SESSION['suma_banca'] = $cartaBanca;
}

// 2. Acción: PEDIR CARTA (Jugador)
if (isset($_POST['pedir'])) {
    $nueva = array_pop($_SESSION['mazo']);
    $_SESSION['mano'][] = $nueva;
    $_SESSION['suma'] += $nueva;
    $_SESSION['cartas_pedidas']++;

    // Si el jugador se pasa de 21, pierde directamente
    if ($_SESSION['suma'] > 21) {
        $_SESSION['acierto'] = false;
        $_SESSION['motivo'] = "Te pasaste de 21 ({$_SESSION['suma']} puntos).";
        unset($_SESSION['mazo'], $_SESSION['mano'], $_SESSION['mano_banca']);
        header("Location: resultado.php");
        exit;
    }

    header("Location: juego.php");
    exit;
}

// 3. Acción: PLANTARSE (Juega la banca automáticamente)
if (isset($_POST['plantarse'])) {
    // La banca saca cartas hasta tener al menos 17 puntos
    while ($_SESSION['suma_banca'] < 17) {
        $cartaBanca = array_pop($_SESSION['mazo']);
        $_SESSION['mano_banca'][] = $cartaBanca;
        $_SESSION['suma_banca'] += $cartaBanca;
    }

    // Comparamos resultados
    if ($_SESSION['suma_banca'] > 21) {
        $_SESSION['acierto'] = true;
        $_SESSION['motivo'] = "La banca se pasó con {$_SESSION['suma_banca']} puntos.";
    } elseif ($_SESSION['suma'] > $_SESSION['suma_banca']) {
        $_SESSION['acierto'] = true;
        $_SESSION['motivo'] = "Ganaste {$_SESSION['suma']} a {$_SESSION['suma_banca']} contra la banca.";
    } else {
        $_SESSION['acierto'] = false;
        $_SESSION['motivo'] = "La banca ganó {$_SESSION['suma_banca']} a {$_SESSION['suma']}.";
    }

    unset($_SESSION['mazo'], $_SESSION['mano'], $_SESSION['mano_banca']);
    header("Location: resultado.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Blackjack con Banca</title>
</head>
<body>

<h1>Bienvenid@, <?php echo htmlspecialchars($_SESSION['login']); ?></h1>

<!-- Zona de la Banca -->
<h3>Carta inicial de la Banca (Puntos: <?php echo $_SESSION['suma_banca']; ?>):</h3>
<?php
foreach ($_SESSION['mano_banca'] as $num) {
    $numFormateado = str_pad($num, 2, "0", STR_PAD_LEFT);
    echo "<img src='img/copas_$numFormateado.jpg' width='120' height='180' style='margin:5px' alt='Carta Banca'>";
}
?>

<hr>

<!-- Zona del Jugador -->
<h2>Tus puntos: <?php echo $_SESSION['suma']; ?> / 21</h2>

<form method="POST">
    <button type="submit" name="pedir">Pedir carta</button>
    <button type="submit" name="plantarse">Plantarse</button>
</form>

<h3>Tus cartas:</h3>
<?php
foreach ($_SESSION['mano'] as $num) {
    $numFormateado = str_pad($num, 2, "0", STR_PAD_LEFT);
    echo "<img src='img/copas_$numFormateado.jpg' width='150' height='220' style='margin:5px' alt='Carta Jugador'>";
}
?>

</body>
</html>
