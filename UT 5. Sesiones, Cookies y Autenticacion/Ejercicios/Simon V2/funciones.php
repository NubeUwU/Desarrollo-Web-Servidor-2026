<?php

function pintar_circulos($circulos, $col_total) {
    $combinacion = [];

    for ($i = 0; $i < $circulos; $i++) {
        $color = $col_total[array_rand($col_total)];
        $combinacion[] = $color;
    }

    return $combinacion;
}

function circulosNegro($circulos, $col_total) {
    for($i = 0; $i < $circulos; $i++){

        echo "<div style='width: 50px; height: 50px; border-radius: 50px; background-color: #000000; display: inline-block; margin: 5px;'></div>";
    }
}

function crearBotones($circulos, $col_total) {
    for($i = 0; $i < count($col_total); $i++){
        $color = $col_total[$i];
        echo "<button style='height: 50px; width: 50px; background-color: $color; display: inline-block; margin: 5px;'></button>";
    }
}