<?php

// Inicializamos variables y un array para almacenar los errores
$nombre = $email = $url = $comentario = $genero = "";
$errores = []; 

// Validacion de los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Guardamos los datos del formulario en variables
    $nombre     = ucwords(trim($_POST["nombre"] ?? ""));
    $email      = trim($_POST["email"] ?? "");
    $url        = trim($_POST["url"] ?? "");
    $comentario = trim($_POST["comentario"] ?? "");
    $genero     = $_POST["genero"] ?? "";

    // Validamos los datos de los campos
    if (empty($nombre)) {
        $errores['nombre'] = "Nombre es requerido";
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)) {
        $errores['nombre'] = "Solo se permiten letras y espacios";
    }

    if (empty($email)) {
        $errores['email'] = "Correo electrónico es requerido";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "Formato de correo incorrecto";
    }

    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        $errores['url'] = "URL inválida";
    }

    if (empty($genero)) {
        $errores['genero'] = "Genero es requerido";
    }
}
?>



<!-- HTML del formulario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

    <h1>Formulario con Validaciones</h1>
    <span style="color: red;">* Campos Requeridos</span>
    <br><br>

    <!-- Formulario -->
    <form action="" method="post">

        <!-- Nombre -->
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre">
        <span style="color: red;">*</span>
        <span style="color: red;"><?php echo $errores['nombre'] ?? ''; ?></span>
        <br><br>

        <!-- Email -->
        <label for="email">E-mail:</label>
        <input type="text" name="email" id="email">
        <span style="color: red;">*</span>
        <span style="color: red;"><?php echo $errores['email'] ?? ''; ?></span>
        <br><br>

        <!-- URL -->
        <label for="url">URL:</label>
        <input type="text" name="url" id="url">
        <span style="color: red;"><?php echo $errores['url'] ?? ''; ?></span>
        <br><br>

        <!-- Comentario -->
        <label for="comentario">Comentario:</label>
        <textarea name="comentario" id="comentario"></textarea>
        <span style="color: red;"></span>
        <br><br>

        <!-- Genero -->
        <span>Genero:</span>
        <input type="radio" name="genero" value="mujer"> Female
        <input type="radio" name="genero" value="hombre"> Male
        <span style="color: red;">*</span>
        <span style="color: red;"><?php echo $errores['genero'] ?? ''; ?></span>
        <br><br>

        <!-- Boton de envio -->
        <input type="submit" value="Submit">
    </form>


<?php
    // Mostramos los resultados
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<h3>Tus datos:</h3>";

        if (!empty($nombre) && !isset($errores['nombre'])) {
            echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
        }

        if (!empty($email) && !isset($errores['email'])) {
            echo "Email: " . htmlspecialchars($email) . "<br>";
        }

        if (!empty($url) && !isset($errores['url'])) {
            echo "URL: " . htmlspecialchars($url) . "<br>";
        }

        if (!empty($comentario) && !isset($errores['comentario'])) {
            echo "Comentario: " . htmlspecialchars($comentario) . "<br>";
        }

        if (!empty($genero) && !isset($errores['genero'])) {
            echo "Género: " . htmlspecialchars($genero) . "<br>";
        }
    }
?>

</body>
</html>
