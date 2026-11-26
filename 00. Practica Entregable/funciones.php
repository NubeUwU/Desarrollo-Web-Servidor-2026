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

function emojisDisponibles(int $num): array {
    return array_slice(emojis, 0, $num);
}

function emojiAleatorio(array $emojis): string {
    return $emojis[array_rand($emojis)];
}

function mostrarEmojis(array $emojis): string {
    $html = '';
    foreach ($emojis as $emoji) {
        $html .= "<span class='emoji' title='$emoji'>&#x" . substr($emoji, 2) . ";</span>";
    }
    return $html;
}

function pintarFormularioContacto(int $i, string $emoji, array $valores, array $errores): string {
    $emojiHtml = mostrarEmojis([$emoji]);

    $nombre    = htmlspecialchars($valores['nombre'] ?? '');
    $apellidos = htmlspecialchars($valores['apellidos'] ?? '');
    $telefono  = htmlspecialchars($valores['telefono'] ?? '');
    $email     = htmlspecialchars($valores['email'] ?? '');

    $errNombre    = isset($errores['nombre'])    ? "<span class='error'>" . htmlspecialchars($errores['nombre']) . "</span>" : "";
    $errApellidos = isset($errores['apellidos']) ? "<span class='error'>" . htmlspecialchars($errores['apellidos']) . "</span>" : "";
    $errTelefono  = isset($errores['telefono'])  ? "<span class='error'>" . htmlspecialchars($errores['telefono']) . "</span>" : "";
    $errEmail     = isset($errores['email'])     ? "<span class='error'>" . htmlspecialchars($errores['email']) . "</span>" : "";

    return <<<HTML
    <div class="bloque-contacto">
        <div class="emoji-col">{$emojiHtml}</div>
        <div class="campos-col">
            <div class="fila-inputs">
                <label>
                    Nombre
                    <input type="text" name="contactos[{$i}][nombre]" value="{$nombre}">
                    {$errNombre}
                </label>
                <label>
                    Apellidos
                    <input type="text" name="contactos[{$i}][apellidos]" value="{$apellidos}">
                    {$errApellidos}
                </label>
            </div>
            <div class="fila-inputs">
                <label>
                    Teléfono
                    <input type="text" name="contactos[{$i}][telefono]" value="{$telefono}">
                    {$errTelefono}
                </label>
                <label>
                    Email
                    <input type="text" name="contactos[{$i}][email]" value="{$email}">
                    {$errEmail}
                </label>
            </div>
        </div>
    </div>
HTML;
}


// FUNCIONES USUARIOS

function registrarUsuario($pdo, string $usuario, string $password): int|false {
    $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE nombre = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$usuario]);

    if ((int)$stmtCheck->fetchColumn() > 0) {
        return false; 
    }

    $claveHash = password_hash($password, PASSWORD_ARGON2ID);
    $sqlInsert = "INSERT INTO usuarios (nombre, clave, rol) VALUES (?, ?, 0)";
    $stmtInsert = $pdo->prepare($sqlInsert);

    if ($stmtInsert->execute([$usuario, $claveHash])) {
        return (int)$pdo->lastInsertId();
    }

    return false;
}


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

function actualizarContactosUsuario($pdo, int $codigoUsuario, array $lote): bool {
    $fechaActual = date('Y-m-d H:i:s');

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO contactos (codigousu, emoji, nombre, apellidos, telefono, email, fecha) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        foreach ($lote as $contacto) {
            $stmt->execute([
                $codigoUsuario,
                $contacto['emoji'],
                $contacto['nombre'],
                $contacto['apellidos'],
                $contacto['telefono'],
                $contacto['email'],
                $fechaActual
            ]);
        }

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return false;
    }
}

function contarContactosUsuario($pdo, int $codigoUsuario): int {
    $sql = "SELECT COUNT(*) FROM contactos WHERE codigousu = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$codigoUsuario]);
    return (int)$stmt->fetchColumn();
}

function cantidadContactosTotales($pdo): array {
    $sql = "SELECT u.codigo, u.nombre, COUNT(c.id) AS total_contactos, MAX(c.fecha) AS ultima_fecha
            FROM usuarios u
            LEFT JOIN contactos c ON u.codigo = c.codigousu
            GROUP BY u.codigo, u.nombre
            ORDER BY total_contactos DESC";
    $stmt = $pdo->query($sql);
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

    // Si no hay resultados, pone estos valores por defecto
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
