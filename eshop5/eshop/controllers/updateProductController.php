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

// Base URL absolue

// ── Validation ────────────────────────────────────────────────────────────────
$errors = [];

$code     = trim($_POST['code']     ?? '');
$name     = trim($_POST['name']     ?? '');
$price    = $_POST['price']         ?? '';
$category = trim($_POST['category'] ?? '');
$stock    = $_POST['stock']         ?? '';

if ($code === '') $errors[] = "Code manquant.";
if ($name === '') $errors[] = "Le nom est obligatoire.";
if (!is_numeric($price) || $price < 0) $errors[] = "Prix invalide.";
if (!ctype_digit((string)$stock) || $stock < 0) $errors[] = "Stock invalide.";

if (!empty($errors)) {
    $_SESSION['flash_errors'] = $errors;
    header("Location: " . $base . "/views/admin/editProduct.php?code=" . urlencode($code));
    exit();
}

// ── Mise à jour ───────────────────────────────────────────────────────────────
$manager = new ProductManager($db);
$p       = $manager->find($code);

if (!$p) {
    http_response_code(404);
    die("Produit introuvable.");
}

$p->name     = $name;
$p->price    = (float)$price;
$p->category = $category;
$p->stock    = (int)$stock;

// ── Upload image (optionnel) ──────────────────────────────────────────────────
$uploadDir = __DIR__ . "/../public/uploads/products/";

if (!empty($_FILES['image']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileMime     = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileMime, $allowedTypes)) {
        $_SESSION['flash_errors'] = ["Type de fichier non autorisé."];
        header("Location: " . $base . "/views/admin/editProduct.php?code=" . urlencode($code));
        exit();
    }

    if ($p->image !== 'default.jpg' && file_exists($uploadDir . $p->image)) {
        unlink($uploadDir . $p->image);
    }

    $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $p->image = uniqid('prod_', true) . '.' . strtolower($ext);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $p->image);
}

$manager->update($p);

$_SESSION['flash_success'] = "Produit mis à jour avec succès.";
header("Location: " . $base . "/views/public/home.php");
exit();
