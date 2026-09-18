<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="Calculadora.php" method="post">
        <label for="Numero1">Número 1:</label>
            <input type="number" id="Num1" name="Num1" required>

        <label for="Numero2">Número 2:</label>
            <input type="number" id="Num2" name="Num2" required>

        <label for="Operacion">Elige una operación:</label>
            <select id="Operacion" name="Operacion">
                <option value="suma">Suma</option>
                <option value="resta">Resta</option>
                <option value="multiplicacion">Multiplicación</option>
                <option value="division">División</option>
            </select>
        <input type="submit" value="Calcular">
    </form>
    
</body>
</html>