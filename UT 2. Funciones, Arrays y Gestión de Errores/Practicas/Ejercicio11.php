<?php

$nombre = "";
$email = "";
$url = "";
$comentario = "";
$genero = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $url = $_POST["url"];
    $comentario = $_POST["comentario"];
    $genero = $_POST["genero"] ?? "";

    $errores = false;

    if (empty($nombre)) {
        $errores = true;
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)) {
        $errores = true;
    }

    if (empty($email)) {
        $errores = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores = true;
    }

    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        $errores = true;
    }

    if (empty($genero)) {
        $errores = true;
    }

    $usuario = [
        "nombre" => $nombre,
        "email" => $email,
        "url" => $url,
        "comentario" => $comentario,
        "genero" => $genero
    ];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
</head>

<body>

    <h1>PHP Form Validation Example</h1>

    <span style="color: red;">* required field.</span>
    <br><br>

    <form action="Ejercicio11.php" method="post">

        <label for="nombre">Name:</label>

        <input type="text" name="nombre" id="nombre"
            value="<?php echo $nombre; ?>">

        <span style="color: red;">*</span>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($nombre)) {
            echo "<span style='color: red;'>Name is required</span>";
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)) {
            echo "<span style='color: red;'>Solo se permiten letras y espacios en blanco</span>";
        }
        ?>

        <br><br>


        <label for="email">E-mail:</label>

        <input type="text" name="email" id="email"
            value="<?php echo $email; ?>">

        <span style="color: red;">*</span>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($email)) {
            echo "<span style='color: red;'>Correo electrónico es requerido</span>";
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<span style='color: red;'>Formato de correo electrónico incorrecto</span>";
        }
        ?>

        <br><br>


        <label for="url">Website:</label>

        <input type="text" name="url" id="url"
            value="<?php echo $url; ?>">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            echo "<span style='color: red;'>URL invalida</span>";
        }
        ?>

        <br><br>


        <label for="comentario">Comment:</label>

        <textarea name="comentario" id="comentario"><?php echo $comentario; ?></textarea>

        <br><br>


        <span>Genero:</span>

        <input type="radio" name="genero" value="mujer"
            <?php echo ($genero == "mujer") ? "checked" : ""; ?>>
        Female

        <input type="radio" name="genero" value="hombre"
            <?php echo ($genero == "hombre") ? "checked" : ""; ?>>
        Male

        <span style="color: red;">*</span>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($genero)) {
            echo "<span style='color: red;'>Gender is required</span>";
        }
        ?>

        <br><br>

        <input type="submit" value="Submit">

    </form>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        echo "<h3>Tus datos:</h3>";

        foreach ($usuario as $campo => $dato) {
            echo $campo . ": " . $dato . "<br>";
        }
    }

    ?>

</body>

</html>