<?php

// FUNCIONES EMOJIS
const emojis = [
    "U+1FAEA",
    "U+1F47D",
    "U+1F47E",
    "U+1F916",
    "U+1F919",
    "U+1F680",
    "U+1F98A", 
    "U+1F525", 
    "U+1F355", 
    "U+26A1",
];

function mostrarEmojis(array $emojis): string {
    $html = '';
    foreach ($emojis as $emoji) {
        $html .= "<span class='emoji' title='$emoji'>&#x" . substr($emoji, 2) . ";</span>";
    }
    return $html;
}


// FUNCIONES USUARIOS
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

function obtenerDatosUsuarios($pdo) {
    $sql = "SELECT u.codigo, u.nombre, u.rol, COUNT(c.id) AS contactos
            FROM usuarios u
            LEFT JOIN contactos c ON u.codigo = c.codigousu
            WHERE u.rol != 1
            GROUP BY u.codigo, u.nombre, u.rol
            ORDER BY u.codigo ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// FUNCIONES CONFIGURACION
function comprobarConfiguracion($pdo) {
    $sql = "SELECT max_lote, num_emojis, max_contactos FROM configuracion LIMIT 1";
    $stmt = $pdo->query($sql);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        return [
            'max_lote' => (int)$resultado['max_lote'],
            'num_emojis' => (int)$resultado['num_emojis'],
            'max_contactos' => (int)$resultado['max_contactos']
        ];
    }

    // Si no hay resultados, pone por defecto estos
    return [
        'max_lote' => 5,
        'num_emojis' => 6,
        'max_contactos' => 10
    ];
}

function actualizarConfiguracion($pdo, $maxLote, $numEmojis, $maxContactos): bool {
    $sql = "UPDATE configuracion SET max_lote = ?, num_emojis = ?, max_contactos = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$maxLote, $numEmojis, $maxContactos]);
}
?>
