<?php
declare(strict_types=1);
require_once 'matematicas.php';

// Datos de prueba
$numeros = [5.3, 7.8, 1.81, 6.4];
$notas = [7.34, 4.31, 3.12, 2.44];

// Mostramos los datos
echo "Números: ";
print_r($numeros);

echo "Notas originales: ";
print_r($notas);


// Calculamos el promedio y lo mostramos
$promedio = calcularPromedio($numeros);
echo "<br> Promedio: " . $promedio . "<br><br>";


// Modificamos las notas y las mostramos
modificarNotas($notas, 1.5);
echo "Notas modificadas: ";
print_r($notas);

echo "<br><br>";


// Llamamos a la función con un argumento incorrecto para probar el manejo de errores
try {
    calcularPromedio("hola");

} catch (TypeError $e) {
    echo "Se ha producido un error: " . $e->getMessage() . "<br>";
}


// Llamamos a la función con un argumento correcto
$promedio = calcularPromedio($numeros);
echo "<br> Promedio: " . $promedio . "<br>";