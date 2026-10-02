<?php
require_once 'notas.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!-- Listado de alumnos -->
    <h1>Listado de alumnos</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Nota</th>
        </tr>
        <?php foreach ($alumnosOrdenados as $alumno): ?>
            <tr>
                <td><?php echo $alumno['nombre']; ?></td>
                <td><?php echo $alumno['nota']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>


    <!-- Listado de aprobados -->
    <h1>Listado de aprobados</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Nota</th>
        </tr>
        <?php foreach ($alumnosAprobados as $alumno): ?>
            <tr>
                <td><?php echo $alumno['nombre']; ?></td>
                <td><?php echo $alumno['nota']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>


    <!-- Estadísticas -->
    <h1>Estadísticas</h1>
    <table border="1">
        <tr>
            <th>Estadística</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td>Suma de las notas</td>
            <td><?php echo $sumaNotas; ?></td>
        </tr>
        <tr>
            <td>Promedio</td>
            <td><?php echo $promedio; ?></td>
        </tr>
    </table>


</body>
</html>