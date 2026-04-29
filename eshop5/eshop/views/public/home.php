<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/views/public', '', $__dir)), '/');
require_once __DIR__ . "/../../controllers/catalogueController.php";

$products = $products ?? [];
$success  = $_SESSION['flash_success'] ?? '';
$errors   = $_SESSION['flash_errors']  ?? [];
unset($_SESSION['flash_success'], $_SESSION['flash_errors']);

$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

// Base URL absolue
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eShop &mdash; Catalogue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0d0d0d;
            --surface:   #161616;
            --card:      #1c1c1e;
            --border:    #2a2a2a;
            --accent:    #e8c97e;
            --accent2:   #c45c3a;
            --text:      #f0ede8;
            --muted:     #7a7672;
            --success:   #4caf78;
            --danger:    #e05252;
            --warning:   #d4a843;
            --radius:    14px;
            --font-head: 'Playfair Display', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            font-weight: 300;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        nav {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px;
            height: 68px;
            background: rgba(13,13,13,.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-logo {
            font-family: var(--font-head);
            font-size: 1.6rem;
            letter-spacing: -.5px;
            color: var(--accent);
            text-decoration: none;
        }
        .nav-logo span { color: var(--text); }
        .nav-actions { display: flex; gap: 10px; align-items: center; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 20px;
            border-radius: 8px;
            border: none; cursor: pointer;
            font-family: var(--font-body);
            font-size: .85rem; font-weight: 500;
            text-decoration: none;
            transition: all .18s ease;
            white-space: nowrap;
        }
        .btn-gold {
            background: var(--accent); color: #111;
        }
        .btn-gold:hover { background: #f0d898; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(232,201,126,.3); }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .btn-ghost {
            background: transparent; color: var(--muted);
            padding: 9px 14px;
        }
        .btn-ghost:hover { color: var(--text); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #c94040; transform: translateY(-1px); }

        .btn-warn { background: var(--warning); color: #111; }
        .btn-warn:hover { background: #e0b84e; transform: translateY(-1px); }

        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #3d9e65; transform: translateY(-1px); }

        .btn-sm { padding: 6px 14px; font-size: .78rem; }

        /* ── HERO ── */
        .hero {
            padding: 80px 48px 60px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 50% at 80% 50%, rgba(232,201,126,.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-label {
            display: inline-block;
            font-size: .72rem; letter-spacing: 3px; text-transform: uppercase;
            color: var(--accent); margin-bottom: 18px;
            border: 1px solid rgba(232,201,126,.3);
            padding: 4px 12px; border-radius: 4px;
        }
        .hero h1 {
            font-family: var(--font-head);
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: 1.1;
            max-width: 520px;
            margin-bottom: 16px;
        }
        .hero h1 em { color: var(--accent); font-style: normal; }
        .hero p { color: var(--muted); font-size: 1rem; max-width: 400px; line-height: 1.7; }

        /* ── SEARCH & FILTERS ── */
        .toolbar {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
            padding: 0 48px 32px;
        }
        .search-wrap {
            display: flex; align-items: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            flex: 1; min-width: 240px; max-width: 400px;
            transition: border-color .18s;
        }
        .search-wrap:focus-within { border-color: var(--accent); }
        .search-wrap svg { color: var(--muted); flex-shrink: 0; }
        .search-wrap input {
            flex: 1; background: none; border: none; outline: none;
            color: var(--text); font-family: var(--font-body);
            font-size: .9rem; padding: 11px 10px;
        }
        .search-wrap input::placeholder { color: var(--muted); }
        .search-clear {
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 1rem; padding: 4px;
        }
        .search-clear:hover { color: var(--text); }

        .count-badge {
            font-size: .8rem; color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 5px 14px; border-radius: 20px;
        }
        .count-badge strong { color: var(--accent); }

        /* ── FLASH ── */
        .flash {
            margin: 0 48px 24px;
            padding: 14px 20px;
            border-radius: var(--radius);
            font-size: .88rem;
            display: flex; align-items: center; gap: 10px;
        }
        .flash-success { background: rgba(76,175,120,.12); border: 1px solid rgba(76,175,120,.3); color: var(--success); }
        .flash-error   { background: rgba(224,82,82,.12);  border: 1px solid rgba(224,82,82,.3);  color: var(--danger); }

        /* ── GRID ── */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            padding: 0 48px 80px;
        }

        /* ── CARD ── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
            animation: fadeUp .4s ease both;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0,0,0,.5);
            border-color: rgba(232,201,126,.2);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-img {
            width: 100%; height: 210px;
            object-fit: cover;
            display: block;
            background: var(--surface);
            transition: transform .35s ease;
        }
        .card:hover .card-img { transform: scale(1.04); }
        .card-img-wrap { overflow: hidden; position: relative; display: block; cursor: pointer; text-decoration: none; }

        .badge-stock {
            position: absolute; top: 12px; right: 12px;
            font-size: .7rem; font-weight: 500; letter-spacing: .5px;
            padding: 3px 10px; border-radius: 20px;
        }
        .badge-in  { background: rgba(76,175,120,.85); color: #fff; }
        .badge-out { background: rgba(224,82,82,.75);  color: #fff; }

        .card-body { padding: 18px 20px 20px; }

        .card-category {
            font-size: .7rem; text-transform: uppercase; letter-spacing: 2px;
            color: var(--accent); margin-bottom: 6px;
        }
        .card-title {
            font-family: var(--font-head);
            font-size: 1.15rem; margin-bottom: 4px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .card-code { font-size: .75rem; color: var(--muted); margin-bottom: 14px; }

        .card-footer-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-top: 4px;
        }
        .card-price {
            font-family: var(--font-head);
            font-size: 1.35rem; color: var(--accent);
        }
        .card-price span { font-size: .75rem; color: var(--muted); font-family: var(--font-body); }

        .card-actions { display: flex; gap: 8px; margin-top: 14px; }

        /* ── EMPTY STATE ── */
        .empty {
            grid-column: 1/-1;
            text-align: center; padding: 80px 20px;
            color: var(--muted);
        }
        .empty-icon { font-size: 3rem; margin-bottom: 14px; }
        .empty h3 { font-family: var(--font-head); color: var(--text); margin-bottom: 8px; }

        /* ── ADMIN FAB ── */
        .fab {
            position: fixed; bottom: 32px; right: 32px;
            background: var(--accent); color: #111;
            border: none; border-radius: 50%;
            width: 56px; height: 56px; font-size: 1.5rem;
            cursor: pointer; box-shadow: 0 6px 24px rgba(232,201,126,.4);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: transform .18s, box-shadow .18s;
            z-index: 50;
        }
        .fab:hover { transform: scale(1.1); box-shadow: 0 10px 32px rgba(232,201,126,.5); }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid var(--border);
            text-align: center; padding: 24px;
            font-size: .78rem; color: var(--muted);
        }

        @media (max-width: 600px) {
            nav, .hero, .toolbar, .grid { padding-left: 20px; padding-right: 20px; }
            .flash { margin-left: 20px; margin-right: 20px; }
            nav { padding: 0 16px; height: auto; min-height: 68px; flex-wrap: wrap; gap: 8px; padding-top: 10px; padding-bottom: 10px; }
            .nav-actions { flex-wrap: wrap; gap: 6px; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
    <a class="nav-logo" href="<?= $base ?>/views/public/home.php">e<span>Shop</span></a>
    <div class="nav-actions">
        <?php if ($isAdmin): ?>
            <a href="<?= $base ?>/views/admin/dashboard.php" class="btn btn-outline btn-sm">Dashboard</a>
            <a href="<?= $base ?>/auth/logout.php"  class="btn btn-ghost  btn-sm">D&eacute;connexion</a>
        <?php else: ?>
            <a href="<?= $base ?>/auth/login.php" class="btn btn-gold btn-sm">Connexion Admin</a>
        <?php endif; ?>
    </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-label">Collection 2025</div>
    <h1>D&eacute;couvrez nos <em>meilleurs</em> produits</h1>
    <p>Une s&eacute;lection soign&eacute;e pour chaque besoin &mdash; qualit&eacute;, style et prix justes.</p>
</section>

<!-- ── FLASH MESSAGES ── -->
<?php if ($success): ?>
    <div class="flash flash-success">&#10003; <?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="flash flash-error">
        <?php foreach ($errors as $e): ?>
            <div>&#10005; <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- ── TOOLBAR ── -->
<div class="toolbar">
    <form method="GET" style="display:contents">
        <div class="search-wrap">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input name="search"
                   placeholder="Rechercher un produit&hellip;"
                   value="<?= htmlspecialchars($search) ?>">
            <?php if ($search !== ''): ?>
                <button type="submit" name="search" value="" class="search-clear" title="Effacer">&#10005;</button>
            <?php endif; ?>
        </div>
        <button class="btn btn-gold">Rechercher</button>
    </form>

    <div class="count-badge">
        <strong><?= count($products) ?></strong> produit<?= count($products) > 1 ? 's' : '' ?>
        <?= $search !== '' ? 'trouv&eacute;' . (count($products) > 1 ? 's' : '') : '' ?>
    </div>
</div>

<!-- ── PRODUCT GRID ── -->
<div class="grid">

    <?php if (!empty($products)): ?>
        <?php foreach ($products as $i => $p): ?>
        <div class="card" style="animation-delay: <?= $i * 60 ?>ms">
            <a href="<?= $base ?>/views/public/product.php?code=<?= urlencode($p->code) ?>" class="card-img-wrap">
                <img class="card-img"
                     src="<?= $base ?>/public/uploads/products/<?= htmlspecialchars($p->image) ?>"
                     alt="<?= htmlspecialchars($p->name) ?>"
                     onerror="this.src='https://placehold.co/400x210/1c1c1e/7a7672?text=No+Image'">
                <span class="badge-stock <?= $p->stock > 0 ? 'badge-in' : 'badge-out' ?>">
                    <?= $p->stock > 0 ? "En stock" : "&Eacute;puis&eacute;" ?>
                </span>
            </a>

            <div class="card-body">
                <div class="card-category"><?= htmlspecialchars($p->category) ?: 'G&eacute;n&eacute;ral' ?></div>
                <div class="card-title"><?= htmlspecialchars($p->name) ?></div>
                <div class="card-code">R&eacute;f. <?= htmlspecialchars($p->code) ?> &middot; Stock: <?= $p->stock ?></div>

                <div class="card-footer-row">
                    <div class="card-price">
                        <?= number_format($p->price, 2) ?>
                        <span>DT</span>
                    </div>
                </div>


            </div>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="empty">
            <div class="empty-icon">&#128269;</div>
            <h3>Aucun produit trouv&eacute;</h3>
            <p><?= $search !== '' ? "Essayez un autre mot-cl&eacute;." : "Le catalogue est vide pour l'instant." ?></p>
        </div>
    <?php endif; ?>

</div>

<!-- FAB admin uniquement -->
<?php if ($isAdmin): ?>
    <a href="<?= $base ?>/views/admin/addProduct.php" class="fab" title="Ajouter un produit">+</a>
<?php endif; ?>

<footer>
    &copy; 2025 eShop &mdash; Tous droits r&eacute;serv&eacute;s
</footer>

</body>
</html>
