<?php

function pintar_circulos($circulos, $col_total){
    for($i = 0; $i < $circulos; $i++){
        $color = $col_total[array_rand($col_total)];

        echo "<div style='width: 50px; height: 50px; border-radius: 50px; background-color: $color; display: inline-block; margin: 5px;'></div>";
    }
}

?>