<?php
$n= $_POST['nombre'];
$p= $_POST['password'];

if ($n == "Piscolabis" && $p == "1234"){
    echo "Bienvenido $n<br><br>";
}
else{
    echo "Usuario o contraseña incorrectos<br><br>";
}

?>