<!-- Bloque PHP -->
<?php
require_once 'funciones.php';

// Si se ha enviado el formulario, se ejecuta el codigo.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Creamos un array con colores y lo mezclamos
    $colores = ['red', 'green', 'blue', 'yellow', 'purple', 'orange', 'pink', 'cyan', 'magenta', 'lime'];
    shuffle($colores);

    // Recogemos las variables del formulario y las guardamos
    $circulos = $_POST['cantidad'];
    $color = $_POST['col'];

    $col_total = array_slice($colores, 0, $color);

    // Llamamos a la funcion que crea y pinta los circulos y los mostramos
    echo "<h1>Su jugada es:</h1><br>";
    pintar_circulos($circulos, $col_total);
} 

else { 
?>

<!-- Bloque HTML -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Bienvenido al Simon</h1>

    <form action="index.php" method="post">

        <!-- Cantidad de Circulos -->
        <label for="cantidad">¿Cuantos círculos quiere?</label>
        <select name="cantidad">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br>

        <!-- Cantidad de Colores -->
        <label for="color">¿Cuantos colores quiere?</label>
        <select name="col">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br>

        <!-- Boton de enviar -->
        <input type="submit" name="jugar" value="Jugar">
    </form>

</body>
</html>

<?php 
} 
?>