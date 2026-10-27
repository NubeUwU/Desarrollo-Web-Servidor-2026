<?php

function comprobarUsuario($pdo, $usuario, $password) {
    $sql = "SELECT codigo, nombre, clave, rol FROM usuarios WHERE nombre = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);

    $resultado = $stmt->fetch();

    if ($resultado && password_verify($password, $resultado['clave'])) {
        return [
            'codigo' => $resultado['codigo'],
            'usuario' => $resultado['nombre'],
            'rol' => $resultado['rol']
        ];
    } else {
        return false;
    }
}

?>