<!-- Bloque PHP -->
<?php
session_start();
require_once 'funciones.php';

// Si se ha enviado el formulario, se ejecuta el codigo.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Creamos un array con colores y lo mezclamos
    $listaColores = ['#FF5733', '#02558d', '#2ECC71', '#F1C40F', '#9B59B6', '#E67E22', '#973914', '#00CED1', '#054a39', '#7FFF00'];
    shuffle($listaColores);

    // Recogemos las variables del formulario y las guardamos
    $_SESSION['circulos'] = $_POST['cCirculos'];
    $_SESSION['colores'] = $_POST['cColores'];

    $col_total = array_slice($listaColores, 0, $_SESSION['colores']);

    $_SESSION['combinacion'] = pintar_circulos($_SESSION['circulos'], $col_total);
    header('Location: mostrar_combinacion.php');
    
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
        <label for="Circulos">¿Cuantos círculos quiere?</label>
        <select name="cCirculos">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br>

        <!-- Cantidad de Colores -->
        <label for="Colores">¿Cuantos colores quiere?</label>
        <select name="cColores">
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