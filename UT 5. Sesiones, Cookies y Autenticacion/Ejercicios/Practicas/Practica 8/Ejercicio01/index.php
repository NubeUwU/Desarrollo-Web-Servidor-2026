<?php
session_start();
$contador = $_SESSION['contador'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Contador</h1>
    <form method="POST" action="contador.php">
        <div style="font-size: 30px;">
        <button type="submit" name="restar" style="font-size: 2.5rem; width: 45px; height: 45px;">-</button>
        <?php echo $contador; ?>
        <button type="submit" name="sumar" style="font-size: 2.5rem; width: 45px; height: 45px;">+</button>
        <br><br>
        <button type="submit" name="reset"">Poner a cero</button>
        </div>
    </form>
</body>
</html>