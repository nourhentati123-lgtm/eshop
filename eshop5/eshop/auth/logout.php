<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/auth', '', $__dir)), '/');
session_unset();
session_destroy();
header("Location: " . $base . "/views/public/home.php");
exit();
