<?php
require_once __DIR__ . "/../config/db_connect.php";
require_once __DIR__ . "/../models/Product.php";
require_once __DIR__ . "/../models/ProductManager.php";

$manager  = new ProductManager($db);
$search   = trim($_GET['search'] ?? '');
$products = !empty($search) ? $manager->search($search) : $manager->getAll();
