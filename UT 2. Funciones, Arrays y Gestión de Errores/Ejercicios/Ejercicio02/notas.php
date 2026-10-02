<?php

// Bloque de funciones
function obtenerAprobados($alumnos){
    return array_filter(
        $alumnos,
        fn($alumno) => $alumno['nota'] >= 5.0
    );
}

function ordenarPorNota($alumnos){
    usort(
        $alumnos,
        fn($a, $b) => $b['nota'] <=> $a['nota']
    );

    return $alumnos;
}

function calcularSuma($alumnos){
    return array_reduce(
        $alumnos,
        fn($total, $alumno) => $total + $alumno['nota'],
        0
    );
}

function calcularPromedio($alumnos){
    return calcularSuma($alumnos) / count($alumnos);
}


// Array con los datos de los alumnos
$alumnos = [
    ['nombre' => 'Ana', 'nota' => 8.5],
    ['nombre' => 'Pedro', 'nota' => 1.2],
    ['nombre' => 'Alejandro', 'nota' => 6.71],
    ['nombre' => 'Marta', 'nota' => 9.25],
    ['nombre' => 'Javier', 'nota' => 3.4],
    ['nombre' => 'Laura', 'nota' => 6.1]
];


// Ejecutamos las funciones
$alumnosOrdenados = ordenarPorNota($alumnos);

$alumnosAprobados = obtenerAprobados($alumnosOrdenados);

$sumaNotas = calcularSuma($alumnos);

$promedio = calcularPromedio($alumnos);

?>