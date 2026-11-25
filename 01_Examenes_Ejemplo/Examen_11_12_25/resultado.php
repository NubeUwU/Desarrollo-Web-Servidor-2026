<?php
session_start();

// 1. Verificar que el usuario está autenticado
if (!isset($_SESSION['login'])) {
    header("Location: login.php"); // Cambia esto por tu página de inicio de sesión
    exit();
}

function conectarDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cartas";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

function actualizarDatos($conn, $login, $puntos, $intentos) {
    $sql = "UPDATE jugador SET puntos = puntos + ?, extra = extra + ? WHERE login = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Error al preparar la consulta: " . $conn->error);

    $stmt->bind_param("iis", $puntos, $intentos, $login);
    if (!$stmt->execute()) {
        die("Error al actualizar los datos: " . $stmt->error);
    }
    $stmt->close();
}

function mostrarDatos($conn) {
    $sql = "SELECT nombre, puntos, extra FROM jugador";
    $resultado = $conn->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td style='border: 3px double black; padding: 5px;'>" . htmlspecialchars($fila["nombre"]) . "</td>
                    <td style='border: 3px double black; padding: 5px;'>" . htmlspecialchars($fila["puntos"]) . "</td>
                    <td style='border: 3px double black; padding: 5px;'>" . htmlspecialchars($fila["extra"]) . "</td>
                  </tr>";
        }
    }
}

$conn = conectarDB();
$hayResultadoTurno = isset($_SESSION['acierto']);

if ($hayResultadoTurno) {
    $acierto = (bool) $_SESSION['acierto'];
    $intentos = (int) ($_SESSION['volteos'] ?? 0);
    $pareja1 = $_SESSION['pareja1'] ?? '';
    $pareja2 = $_SESSION['pareja2'] ?? '';
    $puntosTurno = $acierto ? 1 : -1;

    $mensaje = $acierto
        ? "Se le sumará 1 punto, así como $intentos intentos."
        : "Se le restará 1 punto, así como $intentos intentos.";

    // Actualizamos la BD una única vez
    actualizarDatos($conn, $_SESSION['login'], $puntosTurno, $intentos);

    // Limpiamos la sesión para evitar duplicados al recargar
    unset($_SESSION['acierto'], $_SESSION['volteos'], $_SESSION['pareja1'], $_SESSION['pareja2']);
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

    <?php if ($hayResultadoTurno): ?>
        <?php if ($acierto): ?>
            <h2>Acertó posiciones <?php echo htmlspecialchars($pareja1); ?> y <?php echo htmlspecialchars($pareja2); ?> después de <?php echo $intentos; ?> intentos</h2>
        <?php else: ?>
            <h2>Falló posiciones <?php echo htmlspecialchars($pareja1); ?> y <?php echo htmlspecialchars($pareja2); ?> después de <?php echo $intentos; ?> intentos</h2>
        <?php endif; ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php else: ?>
        <p>No hay resultados recientes de partida para mostrar.</p>
    <?php endif; ?>

    <h3>Puntos por jugador</h3>
    <table style="border: 2px solid black;">
        <tr>
            <th>Nombre</th>
            <th>Puntos</th>
            <th>Extra</th>
        </tr>
        <?php mostrarDatos($conn); ?>
    </table>
</body>
</html>
<?php
$conn->close();
?>
