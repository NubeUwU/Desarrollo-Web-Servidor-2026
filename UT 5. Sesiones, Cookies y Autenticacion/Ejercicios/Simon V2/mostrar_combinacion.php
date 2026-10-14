<?php
session_start();
include_once 'funciones.php'

// Creamos una jugada, del mismo tamaño que la combinacion usada previamente
$_SESSION['jugada'] = array_fill(0, count($_SESSION['combinacion']), '#000000');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        mostrar_jugada($jugada, $circulos);
    ?>  
</body>
</html>