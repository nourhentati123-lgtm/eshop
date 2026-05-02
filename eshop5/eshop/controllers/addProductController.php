<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/controllers', '', $__dir)), '/');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Accès refusé");
}

require_once __DIR__ . "/../config/db_connect.php";
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../models/ProductManager.php";


// ── Validation ────────────────────────────────────────────────────────────────
$errors = [];

$code     = trim($_POST['code']     ?? '');
$name     = trim($_POST['name']     ?? '');
$price    = $_POST['price']         ?? '';
$category = trim($_POST['category'] ?? '');
$stock    = $_POST['stock']         ?? '';

if ($code     === '') $errors[] = "Le code est obligatoire.";
if ($name     === '') $errors[] = "Le nom est obligatoire.";
if (!is_numeric($price) || $price < 0) $errors[] = "Prix invalide.";
if (!ctype_digit((string)$stock) || $stock < 0) $errors[] = "Stock invalide.";

if (empty($errors)) {
    $manager = new ProductManager($db);
    if ($manager->codeExists($code)) {
        $errors[] = "Ce code produit existe déjà.";
    }
}

if (!empty($errors)) {
    $_SESSION['flash_errors'] = $errors;
    header("Location: " . $base . "/views/admin/addProduct.php");
    exit();
}

// ── Upload image ──────────────────────────────────────────────────────────────
$image     = "default.jpg";
$uploadDir = __DIR__ . "/../public/uploads/products/";


if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!empty($_FILES['image']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileMime     = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileMime, $allowedTypes)) {
        $_SESSION['flash_errors'] = ["Type de fichier non autorisé. (jpg, png, gif, webp uniquement)"];
        header("Location: " . $base . "/views/admin/addProduct.php");
        exit();
    }

    $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('prod_', true) . '.' . strtolower($ext);

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
        $_SESSION['flash_errors'] = ["error"];
        header("Location: " . $base . "/views/admin/addProduct.php");
        exit();
    }

    $image = $filename;
}

// ── Insertion ─────────────────────────────────────────────────────────────────
if (!isset($manager)) $manager = new ProductManager($db);
$p           = new Product();
$p->code     = $code;
$p->name     = $name;
$p->price    = (float)$price;
$p->category = $category;
$p->stock    = (int)$stock;
$p->image    = $image;
$manager->insert($p);

$_SESSION['flash_success'] = "Produit ajouté avec succès.";
header("Location: " . $base . "/views/public/home.php");
exit();