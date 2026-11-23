<!DOCTYPE html>
</html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="inicio.php">
        <p>Usuario: <?php echo $nombre; ?></p>
        <p>Contactos a grabar: <?php echo $num; ?></p>
        <input type="submit" name="incrementar" value="Incrementar">
        <input type="submit" name="grabar" value="Grabar">
    </form>
    <?php if ($error !== ''): ?>
        <span style="color: red;"><?php echo $error; ?></span>
    <?php endif; ?>
</body>
</html>