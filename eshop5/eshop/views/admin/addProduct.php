<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/views/admin', '', $__dir)), '/');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { http_response_code(403); die("Acc&egrave;s refus&eacute;"); }
$errors  = $_SESSION['flash_errors']  ?? [];
$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_errors'], $_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter Produit &mdash; eShop</title>
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
        .nav-logo { font-family: var(--font-head); font-size: 1.5rem; color: var(--accent); text-decoration: none; }
        .nav-logo span { color: var(--text); }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 8px; border: none;
            cursor: pointer; font-family: var(--font-body); font-size: .85rem; font-weight: 500;
            text-decoration: none; transition: all .18s;
        }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-gold { background: var(--accent); color: #111; }
        .btn-gold:hover { background: #f0d898; transform: translateY(-1px); }

        .container { max-width: 620px; margin: 0 auto; padding: 52px 24px 80px; }
        .page-title { font-family: var(--font-head); font-size: 2rem; margin-bottom: 6px; }
        .page-sub   { color: var(--muted); font-size: .88rem; margin-bottom: 36px; }

        .flash-success { background: rgba(76,175,120,.1); border: 1px solid rgba(76,175,120,.3); color: var(--success); padding: 14px 18px; border-radius: 10px; margin-bottom: 24px; font-size:.87rem; }
        .flash-error   { background: rgba(224,82,82,.1);  border: 1px solid rgba(224,82,82,.3);  color: var(--danger);  padding: 14px 18px; border-radius: 10px; margin-bottom: 24px; font-size:.87rem; }

        .form-card { background: var(--card); border: 1px solid var(--border); border-radius: 18px; padding: 36px 32px; }
        .field { margin-bottom: 22px; }
        label { display: block; font-size: .75rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
        input, select {
            width: 100%; background: var(--bg); border: 1px solid var(--border);
            border-radius: 10px; color: var(--text);
            font-family: var(--font-body); font-size: .9rem; padding: 12px 16px;
            outline: none; transition: border-color .18s;
        }
        input:focus, select:focus { border-color: var(--accent); }
        input[type="file"] { padding: 10px 16px; color: var(--muted); cursor: pointer; }
        .hint { font-size: .74rem; color: var(--muted); margin-top: 6px; }

        .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .submit-row { margin-top: 30px; display: flex; gap: 12px; }
        .submit-row .btn { flex: 1; justify-content: center; padding: 14px; font-size: .92rem; }
    </style>
</head>
<body>
<nav>
    <a class="nav-logo" href="<?= $base ?>/views/public/home.php">e<span>Shop</span></a>
    <a href="<?= $base ?>/views/admin/dashboard.php" class="btn btn-outline">&larr; Dashboard</a>
</nav>

<div class="container">
    <h1 class="page-title">Ajouter un produit</h1>
    <p class="page-sub">Remplissez les informations ci-dessous pour cr&eacute;er une nouvelle fiche.</p>

    <?php if ($success): ?>
        <div class="flash-success">&#10003; <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="flash-error">
            <?php foreach ($errors as $e): ?><div>&#10005; <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" action="<?= $base ?>/controllers/addProductController.php">

            <div class="row2">
                <div class="field">
                    <label>Code *</label>
                    <input name="code" placeholder="Ex: PROD001" required>
                </div>
                <div class="field">
                    <label>Cat&eacute;gorie</label>
                    <input name="category" placeholder="Ex: &Eacute;lectronique">
                </div>
            </div>

            <div class="field">
                <label>Nom du produit *</label>
                <input name="name" placeholder="Nom complet du produit" required>
            </div>

            <div class="row2">
                <div class="field">
                    <label>Prix (DT) *</label>
                    <input name="price" type="number" step="0.01" min="0" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Stock</label>
                    <input name="stock" type="number" min="0" placeholder="0">
                </div>
            </div>

            <div class="field">
                <label>Image du produit</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="hint">Formats : JPG, PNG, GIF, WebP &mdash; Laisser vide pour utiliser l'image par d&eacute;faut.</div>
            </div>

            <div class="submit-row">
                <a href="<?= $base ?>/views/admin/dashboard.php" class="btn btn-outline">Annuler</a>
                <button class="btn btn-gold" type="submit">&#10133; Ajouter le produit</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
