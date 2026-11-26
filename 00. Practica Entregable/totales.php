<?php
    session_start();
    require_once 'conexion.php';
    require_once 'funciones.php';

    // Si no hay rol en sesion, se sale al login
    if (!isset($_SESSION['rol'])) {
        header("Location: salir.php");
        exit;
    }
    $cod = $_SESSION['codigo'] ?? 0;
    $usuario = $_SESSION['usuario'] ?? '';

    // Obtenemos el máximo de contactos permitido desde la configuración
    $config = comprobarConfiguracion($pdo);
    $maxContactos = (int)$config['max_contactos'];

    $totales = cantidadContactosTotales($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AGENDA — Totales</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="caja">
        <h2>AGENDA — Totales</h2>
        <p><strong><?php echo htmlspecialchars($usuario); ?></strong>, estos son los contactos guardados:</p>

        <table>
            <tr>
                <th>Cód.</th>
                <th>Nombre</th>
                <th>Cont. (Max. <?php echo $maxContactos; ?>)</th>
                <th>%</th>
                <th>Último alta</th>
                <th style="width: 150px;">Gráfica</th>
            </tr>
            <?php foreach ($totales as $fila): ?>
                <?php
                    $cont = (int)$fila['total_contactos'];
                    $porcentaje = ($maxContactos > 0) ? ($cont * 100) / $maxContactos : 0;

                    // Si supera el 100% de los contactos, se deja al 100%
                    if ($porcentaje > 100) {
                        $porcentaje = 100;
                    }

                    $anchoBarra = min(100, round($porcentaje));
                    $fecha = $fila['ultima_fecha'] ? date('d/m/Y', strtotime($fila['ultima_fecha'])) : '—';
                    $clase = ((int)$fila['codigo'] === (int)$cod) ? 'activa' : '';
                ?>
                <tr class="<?php echo $clase; ?>">
                    <td><?php echo (int)$fila['codigo']; ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo $cont; ?></td>
                    <td><?php echo number_format($porcentaje, 1, ',', ''); ?></td>
                    <td><?php echo $fecha; ?></td>
                    <td>
                        <?php if ($cont > 0): ?>
                            <div class="barra" style="width: <?php echo $anchoBarra; ?>%;"></div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="enlaces">
            <?php 
            if ($_SESSION['rol'] == 0) {
                echo '<a href="inicio.php">Grabar más contactos</a> |';
            }
            ?>
            <a href="salir.php">Cerrar sesión</a>
        </p>
    </div>
</body>
</html>
                            