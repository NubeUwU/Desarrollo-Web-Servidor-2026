<?php
session_start();

if (isset($_POST['restar'])) {
 $_SESSION['contador']--;
}

if (isset($_POST['sumar'])) {
 $_SESSION['contador']++;
}

if (!isset($_SESSION['contador']) || isset($_POST['reset'])) {
 $_SESSION['contador'] = 0;
}

header('Location: index.php');
exit();
?>