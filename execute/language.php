<?php
session_start();
require_once '../config.php';
require_once '../function.php';

$lang = $_POST['language'];


unset($_SESSION['lang']);
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + 3600, '/');


?>