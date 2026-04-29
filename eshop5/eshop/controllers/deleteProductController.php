<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/controllers', '', $__dir)), '/');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Accès refusé");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: " . $base . "/views/public/home.php");
    exit();
}

require_once __DIR__ . "/../config/db_connect.php";
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../models/ProductManager.php";

$code = trim($_POST['code'] ?? '');

if ($code === '') {
    header("Location: " . $base . "/views/public/home.php");
    exit();
}

$manager = new ProductManager($db);
$p       = $manager->find($code);

if ($p && $p->image !== 'default.jpg') {
    $imgPath = __DIR__ . "/../public/uploads/products/" . $p->image;
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

$manager->delete($code);

$_SESSION['flash_success'] = "Produit supprimé.";
header("Location: " . $base . "/views/public/home.php");
exit();
