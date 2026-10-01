<?php
declare(strict_types=1);

// Funcion para calcular el promedio
function calcularPromedio(array $numeros): float {
    return array_sum($numeros) / count($numeros);

}

// Funcion para modificar las notas
function modificarNotas(array &$notas, float $puntos): void
{
    foreach ($notas as &$nota) {
        $nota += $puntos;
    }
}

?>