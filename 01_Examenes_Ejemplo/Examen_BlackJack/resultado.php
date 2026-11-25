<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

function conectarDB() {
    $conn = new mysqli("localhost", "root", "", "blackjack");
    if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);
    return $conn;
}

function actualizarDatos($conn, $login, $puntos, $cartasPedidas) {
    $sql = "UPDATE jugador 
            SET puntos = COALESCE(puntos, 0) + ?, 
                extra = COALESCE(extra, 0) + ? 
            WHERE login = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Error al preparar consulta: " . $conn->error);

    $stmt->bind_param("iis", $puntos, $cartasPedidas, $login);
    if (!$stmt->execute()) die("Error al actualizar datos: " . $stmt->error);
    $stmt->close();
}

function mostrarDatos($conn) {
    $sql = "SELECT nombre, puntos, extra FROM jugador ORDER BY puntos DESC";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $puntos = (int)$fila["puntos"];

            // Calculamos el ancho de la barra: 20px por cada punto (mínimo 0px, máximo 300px)
            $anchoBarra = min(300, max(0, $puntos * 20));

            echo "<tr>
                    <td style='border: 3px double black; padding: 5px;'>" . htmlspecialchars($fila["nombre"]) . "</td>
                    <td style='border: 3px double black; padding: 5px;'>" . $puntos . "</td>
                    <td style='border: 3px double black; padding: 5px;'>" . htmlspecialchars((string)$fila["extra"]) . "</td>
                    <td style='border: 3px double black; padding: 5px; width: 310px;'>
                        <div style='background-color: #4CAF50; height: 20px; width: {$anchoBarra}px;'></div>
                    </td>
                  </tr>";
        }
    }
}

$conn = conectarDB();
$hayResultado = isset($_SESSION['acierto']);

if ($hayResultado) {
    $acierto = $_SESSION['acierto'];
    $motivo = $_SESSION['motivo'] ?? '';
    $cartasPedidas = $_SESSION['cartas_pedidas'] ?? 0;

    // Si gana suma 1 punto; si pierde suma 0 (no resta puntos)
    $puntosTurno = $acierto ? 1 : 0;

    $mensaje = $acierto
        ? "$motivo Se te suma 1 punto y $cartasPedidas cartas."
        : "$motivo Has perdido, se suman $cartasPedidas cartas al historial.";

    actualizarDatos($conn, $_SESSION['login'], $puntosTurno, $cartasPedidas);

    unset($_SESSION['acierto'], $_SESSION['motivo'], $_SESSION['suma'], $_SESSION['suma_banca'], $_SESSION['cartas_pedidas']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">  
    <title>Resultado</title>
</head>
<body>

<h1>Bienvenid@, <?php echo htmlspecialchars($_SESSION['login']); ?></h1>

<?php if ($hayResultado): ?>
    <h2><?php echo $acierto ? "¡Ganaste a la Banca!" : "Ganó la Banca"; ?></h2>
    <p><?php echo htmlspecialchars($mensaje); ?></p>
<?php else: ?>
    <p>No hay una partida reciente por evaluar.</p>
<?php endif; ?>

<p><a href="juego.php">Volver a jugar otra partida</a></p>

<h3>Puntos por jugador</h3>
<table style="border: 2px solid black; border-collapse: collapse;">
    <tr>
        <th style="border: 2px solid black; padding: 5px;">Nombre</th>
        <th style="border: 2px solid black; padding: 5px;">Puntos</th>
        <th style="border: 2px solid black; padding: 5px;">Cartas (Extra)</th>
        <th style="border: 2px solid black; padding: 5px;">Gráfico de Aciertos</th>
    </tr>
    <?php mostrarDatos($conn); ?>
</table>

</body>
</html>
<?php
$conn->close();
?>
