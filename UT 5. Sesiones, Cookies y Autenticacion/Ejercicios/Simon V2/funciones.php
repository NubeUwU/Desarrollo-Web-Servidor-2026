<?php

function pintar_circulos($circulos, $col_total){
    $combinacion = [];

    for($i = 0; $i < $circulos; $i++){
        $color = $col_total[array_rand($col_total)];
        $combinacion[] = $color;

        echo "<div style='width: 50px; height: 50px; border-radius: 50px; background-color: $color; display: inline-block; margin: 5px;'></div>";
    }

    $_SESSION['combinacion'] = $combinacion;
}

function mostrar_jugada($jugada, $circulos){
    for($i = 0; $i < $circulos; $i++){
        echo "<div style='width: 50px; height: 50px; border-radius: 50px; background-color: {$jugada[$i]}; display: inline-block; margin: 5px;'></div>";
    }
}

?>