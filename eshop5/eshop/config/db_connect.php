<?php
$dsn  = "mysql:host=localhost;dbname=eshop_db;charset=utf8";
$user = "root";
$psw  = "";

try {
    $db = new PDO($dsn, $user, $psw);
    $db->setAttribute(PDO::ATTR_ERRMODE,       PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}
