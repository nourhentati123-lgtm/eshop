<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/views/admin', '', $__dir)), '/');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . $base . "/views/public/home.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard &mdash; eShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0d0d0d; --surface: #161616; --card: #1c1c1e; --border: #2a2a2a;
            --accent: #e8c97e; --text: #f0ede8; --muted: #7a7672;
            --success: #4caf78; --danger: #e05252;
            --font-head: 'Playfair Display', serif; --font-body: 'DM Sans', sans-serif;
        }
        body { background: var(--bg); color: var(--text); font-family: var(--font-body); font-weight: 300; min-height: 100vh; }
        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 68px;
            background: rgba(13,13,13,.9); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 10;
        }
        .nav-logo { font-family: var(--font-head); font-size: 1.6rem; color: var(--accent); text-decoration: none; }
        .nav-logo span { color: var(--text); }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 22px; border-radius: 10px; border: none;
            cursor: pointer; font-family: var(--font-body); font-size: .88rem; font-weight: 500;
            text-decoration: none; transition: all .18s;
        }
        .container { max-width: 900px; margin: 0 auto; padding: 60px 24px; }
        .welcome { margin-bottom: 48px; }
        .welcome .tag {
            display: inline-block; font-size: .7rem; letter-spacing: 3px; text-transform: uppercase;
            color: var(--accent); border: 1px solid rgba(232,201,126,.3);
            padding: 4px 12px; border-radius: 4px; margin-bottom: 14px;
        }
        .welcome h1 { font-family: var(--font-head); font-size: 2.4rem; margin-bottom: 6px; }
        .welcome p  { color: var(--muted); }
        .actions-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .action-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 16px; padding: 28px 24px;
            text-decoration: none; color: var(--text);
            transition: all .22s;
            display: flex; flex-direction: column; gap: 10px;
        }
        .action-card:hover { border-color: var(--accent); transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.4); }
        .action-icon  { font-size: 1.8rem; }
        .action-title { font-family: var(--font-head); font-size: 1.1rem; }
        .action-desc  { font-size: .82rem; color: var(--muted); line-height: 1.5; }
        .action-card.danger:hover { border-color: var(--danger); }
    </style>
</head>
<body>
<nav>
    <a class="nav-logo" href="<?= $base ?>/views/public/home.php">e<span>Shop</span></a>
    <span style="color:var(--muted); font-size:.85rem">
        &#128100; <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
    </span>
</nav>

<div class="container">
    <div class="welcome">
        <div class="tag">Administration</div>
        <h1>Bonjour, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?> &#128075;</h1>
        <p>G&eacute;rez votre catalogue depuis ce tableau de bord.</p>
    </div>

    <div class="actions-grid">
        <a href="<?= $base ?>/views/admin/addProduct.php" class="action-card">
            <div class="action-icon">&#10133;</div>
            <div class="action-title">Ajouter un produit</div>
            <div class="action-desc">Cr&eacute;er une nouvelle fiche produit avec image et d&eacute;tails.</div>
        </a>

        <a href="<?= $base ?>/views/public/home.php" class="action-card">
            <div class="action-icon">&#128717;</div>
            <div class="action-title">Voir le catalogue</div>
            <div class="action-desc">Parcourir et g&eacute;rer tous les produits disponibles.</div>
        </a>

        <a href="<?= $base ?>/auth/logout.php" class="action-card danger">
            <div class="action-icon">&#128682;</div>
            <div class="action-title">Se d&eacute;connecter</div>
            <div class="action-desc">Fermer la session administrateur en toute s&eacute;curit&eacute;.</div>
        </a>
    </div>
</div>
</body>
</html>
