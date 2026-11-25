<?php
session_start();

if (isset($_POST['entrar'])) {
    $login = $_POST['login'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $conn = new mysqli("localhost", "root", "", "blackjack");
    if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

    $stmt = $conn->prepare("SELECT login FROM jugador WHERE login = ? AND clave = ?");
    $stmt->bind_param("ss", $login, $clave);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['login'] = $login;
        header("Location: juego.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Blackjack</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        Usuario: <input type="text" name="login" required><br><br>
        Contraseña: <input type="password" name="clave" required><br><br>
        <button type="submit" name="entrar">Entrar</button>
    </form>
</body>
</html>
