<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/views/public', '', $__dir)), '/');

require_once __DIR__ . "/../../config/db_connect.php";
require_once __DIR__ . "/../../models/Product.php";
require_once __DIR__ . "/../../models/ProductManager.php";

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$code    = trim($_GET['code'] ?? '');

if ($code === '') {
    header("Location: " . $base . "/views/public/home.php");
    exit();
}

$manager = new ProductManager($db);
$p       = $manager->find($code);

if (!$p) {
    header("Location: " . $base . "/views/public/home.php");
    exit();
}

$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($p->name) ?> &mdash; eShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0d0d0d; --surface: #161616; --card: #1c1c1e; --border: #2a2a2a;
            --accent: #e8c97e; --text: #f0ede8; --muted: #7a7672;
            --danger: #e05252; --warn: #d4a843; --success: #4caf78;
            --font-head: 'Playfair Display', serif; --font-body: 'DM Sans', sans-serif;
        }
        body { background: var(--bg); color: var(--text); font-family: var(--font-body); font-weight: 300; min-height: 100vh; }

        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 68px;
            background: rgba(13,13,13,.9); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 10;
        }
        .nav-logo { font-family: var(--font-head); font-size: 1.5rem; color: var(--accent); text-decoration: none; }
        .nav-logo span { color: var(--text); }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 26px; border-radius: 10px; border: none;
            cursor: pointer; font-family: var(--font-body); font-size: .9rem; font-weight: 500;
            text-decoration: none; transition: all .18s;
        }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-warn    { background: var(--warn); color: #111; }
        .btn-warn:hover { background: #e0b84e; transform: translateY(-1px); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #c94040; transform: translateY(-1px); }

        .container {
            max-width: 860px; margin: 60px auto; padding: 0 24px;
        }

        .product-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden;
            display: grid; grid-template-columns: 1fr 1fr;
            box-shadow: 0 24px 60px rgba(0,0,0,.4);
        }

        .product-img {
            width: 100%; height: 100%; min-height: 360px;
            object-fit: cover; display: block;
        }

        .product-info {
            padding: 40px 36px;
            display: flex; flex-direction: column; gap: 16px;
        }

        .product-category {
            font-size: .7rem; text-transform: uppercase; letter-spacing: 3px;
            color: var(--accent);
        }

        .product-name {
            font-family: var(--font-head); font-size: 2rem; line-height: 1.2;
        }

        .product-code {
            font-size: .8rem; color: var(--muted);
        }

        .badge-stock {
            display: inline-block;
            font-size: .75rem; font-weight: 500;
            padding: 4px 14px; border-radius: 20px; width: fit-content;
        }
        .badge-in  { background: rgba(76,175,120,.15); color: var(--success); border: 1px solid rgba(76,175,120,.3); }
        .badge-out { background: rgba(224,82,82,.12);  color: var(--danger);  border: 1px solid rgba(224,82,82,.3); }

        .divider { border: none; border-top: 1px solid var(--border); margin: 4px 0; }

        .product-price {
            font-family: var(--font-head); font-size: 2.2rem; color: var(--accent);
        }
        .product-price span { font-size: .85rem; color: var(--muted); font-family: var(--font-body); }

        .product-stock-count {
            font-size: .85rem; color: var(--muted);
        }

        .actions { display: flex; gap: 12px; margin-top: auto; flex-wrap: wrap; }

        .flash-success {
            background: rgba(76,175,120,.1); border: 1px solid rgba(76,175,120,.3);
            color: var(--success); padding: 12px 18px; border-radius: 10px;
            margin-bottom: 24px; font-size: .88rem;
        }

        @media (max-width: 640px) {
            .product-card { grid-template-columns: 1fr; }
            .product-img { min-height: 240px; }
            nav { padding: 0 20px; }
        }
    </style>
</head>
<body>

<nav>
    <a class="nav-logo" href="<?= $base ?>/views/public/home.php">e<span>Shop</span></a>
    <a href="<?= $base ?>/views/public/home.php" class="btn btn-outline">&larr; Retour</a>
</nav>

<div class="container">

    <?php if ($success): ?>
        <div class="flash-success">&#10003; <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="product-card">
        <img class="product-img"
             src="<?= $base ?>/public/uploads/products/<?= htmlspecialchars($p->image) ?>"
             alt="<?= htmlspecialchars($p->name) ?>"
             onerror="this.src='https://placehold.co/600x400/1c1c1e/7a7672?text=No+Image'">

        <div class="product-info">
            <div class="product-category"><?= htmlspecialchars($p->category) ?: 'G&eacute;n&eacute;ral' ?></div>
            <div class="product-name"><?= htmlspecialchars($p->name) ?></div>
            <div class="product-code">R&eacute;f. <?= htmlspecialchars($p->code) ?></div>

            <div class="badge-stock <?= $p->stock > 0 ? 'badge-in' : 'badge-out' ?>">
                <?= $p->stock > 0 ? "En stock" : "&Eacute;puis&eacute;" ?>
            </div>

            <hr class="divider">

            <div class="product-price">
                <?= number_format($p->price, 2) ?> <span>DT</span>
            </div>
            <div class="product-stock-count">
                Quantit&eacute; disponible&nbsp;: <strong><?= $p->stock ?></strong>
            </div>

            <?php if ($isAdmin): ?>
            <hr class="divider">
            <div class="actions">
                <a href="<?= $base ?>/views/admin/editProduct.php?code=<?= urlencode($p->code) ?>"
                   class="btn btn-warn">&#9998; Modifier</a>

                <form method="POST"
                      action="<?= $base ?>/controllers/deleteProductController.php"
                      onsubmit="return confirm('Supprimer &laquo; <?= htmlspecialchars(addslashes($p->name)) ?> &raquo; ?')">
                    <input type="hidden" name="code" value="<?= htmlspecialchars($p->code) ?>">
                    <button class="btn btn-danger" type="submit">&#128465; Supprimer</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
