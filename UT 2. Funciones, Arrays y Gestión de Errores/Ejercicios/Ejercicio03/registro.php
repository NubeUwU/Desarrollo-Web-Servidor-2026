<?php

/* Inicialización de variables */
$nombre = "";
$email = "";
$edad = "";
$modulo = "";

$errores = [];
$usuario = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $edad = filter_input(INPUT_POST, "edad", FILTER_VALIDATE_INT);
    $modulo = filter_input(INPUT_POST, "modulo", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }

    if ($email === false || $email === null) {
        $errores[] = "El email no es válido.";
        $email = "";
    }

    if ($edad === false || $edad === null || $edad < 0 || $edad > 120) {
        $errores[] = "La edad no es válida.";
        $edad = "";
    }

    if (empty($modulo)) {
        $errores[] = "Debes seleccionar un módulo.";
    }

    // Si no hay errores, simulamos guardar el usuario
    if (empty($errores)) {

        $usuario = [
            "nombre" => $nombre,
            "email" => $email,
            "edad" => $edad,
            "modulo" => $modulo
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>

<body>

    <!-- Formulario de registro -->
    <h1>Registro</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <label>Nombre completo:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>">

        <br><br>

        <label>Email:</label>
        <input type="text" name="email" value="<?php echo $email; ?>">

        <br><br>

        <label>Edad:</label>
        <input type="text" name="edad" value="<?php echo $edad; ?>">

        <br><br>

        <label>Módulo Formativo:</label>

        <select name="modulo">
            <option value="">Selecciona un módulo</option>
            <option value="DAW">DAW</option>
            <option value="DAM">DAM</option>
            <option value="ASIR">ASIR</option>
        </select>

        <br><br>

        <input type="submit" value="Enviar">

    </form>


    <!-- Se muestran errores si los hay -->
    <?php foreach ($errores as $error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endforeach; ?>


    <!-- Se muestra un mensaje de éxito si el usuario se ha registrado correctamente -->
    <?php if (!empty($usuario)): ?>

        <p style="color: green;">
            Usuario registrado correctamente.
        </p>

    <?php endif; ?>

</body>

</html>