<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != $rolPermitido) {
    header("Location: index.php");
    exit;
}