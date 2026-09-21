<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<!-- Bloque de codigo PHP -->
<?php

    /*Se inicializa la variable mensaje vacía*/
    $mensaje = "";

    /*Se verifica si el formulario ha sido enviado*/
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        /* Variables para los números y la operación */
        $n1 = $_POST['n1'];
        $n2 = $_POST['n2'];
        $operacion = $_POST['operacion'];
        $resultado = 0;

        /* Se realiza la operación según la opción seleccionada */
        $resultado = match ($operacion) {
            'suma' => $n1 + $n2,
            'resta' => $n1 - $n2,
            'multiplicacion' => $n1 * $n2,
            'division' => $n2 != 0 ? $n1 / $n2 : "Error: No se puede dividir por cero",
        };

        /* Se muestra el resultado de la operación */
        $mensaje = "<h2>El resultado de la $operacion de  $n1 y $n2 es: $resultado </h2>";
    }
?>


<!-- Bloque de codigo HTML -->
<body>
    <!-- Formulario HTML para la calculadora -->
    <h1>Calculadora</h1><br><br>
    <form action="Calculadora.php" method="post">
        
        <!-- Primer Número -->
        <label for="n1">Número 1:</label>
        <input type="number" id="n1" name="n1" required><br><br>

        <!-- Segundo Número -->
        <label for="n2">Número 2:</label>
        <input type="number" id="n2" name="n2" required><br><br>

        <!-- Selección de la operación -->
        <label for="Operacion">Elige una operación:</label>
        <select id="Operacion" name="operacion">
            <option value="suma">Suma</option>
            <option value="resta">Resta</option>
            <option value="multiplicacion">Multiplicación</option>
            <option value="division">División</option>
        </select>

        <!-- Botón para enviar el formulario -->
        <input type="submit" value="Calcular">
    </form>

    <!-- Se muestra el resultado de la operación -->
    <?php echo $mensaje; ?>
</body>
</html>