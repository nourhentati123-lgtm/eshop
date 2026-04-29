<?php
session_start();
$__dir  = str_replace('\\', '/', __DIR__);
$__root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base   = rtrim(str_replace($__root, '', str_replace('/auth', '', $__dir)), '/');

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: " . $base . "/views/admin/dashboard.php");
    exit();
}

// ── Identifiants admin uniques ─────────────────────────────
$ADMIN_USERNAME = 'nour';
$ADMIN_EMAIL    = 'nourhentati123@gmail.com';
$ADMIN_PASSWORD = 'noureshopmanger';
// ───────────────────────────────────────────────────────────

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']         ?? '';

    if ($username === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $usernameOk = ($username === $ADMIN_USERNAME || $username === $ADMIN_EMAIL);
        $passwordOk = ($password === $ADMIN_PASSWORD);

        if ($usernameOk && $passwordOk) {
            session_regenerate_id(true);
            $_SESSION['role']     = 'admin';
            $_SESSION['username'] = $ADMIN_USERNAME;
            $_SESSION['email']    = $ADMIN_EMAIL;
            header("Location: " . $base . "/views/admin/dashboard.php");
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion &mdash; eShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:      #0d0d0d;
            --surface: #161616;
            --border:  #2a2a2a;
            --accent:  #e8c97e;
            --text:    #f0ede8;
            --muted:   #7a7672;
            --danger:  #e05252;
            --success: #4caf78;
            --font-head: 'Playfair Display', serif;
            --font-body: 'DM Sans', sans-serif;
        }
        body {
            background: var(--bg); color: var(--text);
            font-family: var(--font-body); font-weight: 300;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(ellipse 50% 60% at 30% 40%, rgba(232,201,126,.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 44px;
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
            box-shadow: 0 24px 60px rgba(0,0,0,.5);
            animation: rise .4s ease;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .logo {
            font-family: var(--font-head);
            font-size: 2rem; color: var(--accent);
            text-align: center; margin-bottom: 6px;
        }
        .logo span { color: var(--text); }
        .subtitle {
            text-align: center; color: var(--muted);
            font-size: .85rem; margin-bottom: 10px;
        }
        .admin-badge {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin: 0 auto 28px;
            background: rgba(76,175,120,.08);
            border: 1px solid rgba(76,175,120,.22);
            border-radius: 20px;
            padding: 5px 14px;
            width: fit-content;
            font-size: .75rem; color: var(--success);
        }
        .admin-badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--success); flex-shrink: 0;
        }
        .field { margin-bottom: 18px; }
        label {
            display: block; font-size: .75rem;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--muted); margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); pointer-events: none;
        }
        input {
            width: 100%;
            background: #0d0d0d;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: var(--font-body); font-size: .9rem;
            padding: 12px 16px 12px 42px;
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232,201,126,.08);
        }
        .hint-box {
            background: rgba(232,201,126,.05);
            border: 1px solid rgba(232,201,126,.14);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: .78rem; color: var(--muted); line-height: 1.7;
        }
        .hint-box strong { color: var(--accent); font-weight: 500; }
        .error {
            background: rgba(224,82,82,.1);
            border: 1px solid rgba(224,82,82,.3);
            color: var(--danger);
            padding: 12px 16px; border-radius: 10px;
            font-size: .84rem; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-login {
            width: 100%; padding: 14px;
            background: var(--accent); color: #111;
            border: none; border-radius: 10px;
            font-family: var(--font-body); font-size: .95rem; font-weight: 500;
            cursor: pointer; margin-top: 8px;
            transition: background .18s, transform .18s, box-shadow .18s;
        }
        .btn-login:hover {
            background: #f0d898;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(232,201,126,.25);
        }
        .back-link {
            display: block; text-align: center;
            margin-top: 24px; font-size: .82rem;
            color: var(--muted); text-decoration: none;
            transition: color .15s;
        }
        .back-link:hover { color: var(--accent); }
        .lock-notice {
            display: flex; align-items: center; gap: 8px;
            margin-top: 28px; padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: .76rem; color: var(--muted);
        }
        @media (max-width: 480px) {
            .card { padding: 36px 24px; margin: 16px; }
        }
    </style>
</head>
<body>
<div class="card">

    <div class="logo">e<span>Shop</span></div>
    <div class="subtitle">Espace administrateur</div>

    <div class="admin-badge">
        <div class="admin-badge-dot"></div>
        Acc&egrave;s r&eacute;serv&eacute; &mdash; Nour
    </div>

    <?php if ($error): ?>
        <div class="error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="hint-box">
        Identifiant&nbsp;: <strong>nour</strong> &nbsp;ou&nbsp;
        <strong>nourhentati123@gmail.com</strong>
    </div>

    <form method="POST">
        <div class="field">
            <label>Identifiant</label>
            <div class="input-wrap">
                <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input name="username"
                       autocomplete="username"
                       placeholder="nour ou email"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required>
            </div>
        </div>

        <div class="field">
            <label>Mot de passe</label>
            <div class="input-wrap">
                <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input name="password"
                       type="password"
                       autocomplete="current-password"
                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                       required>
            </div>
        </div>

        <button class="btn-login" type="submit">Se connecter &rarr;</button>
    </form>

    <a class="back-link" href="<?= $base ?>/views/public/home.php">&larr; Retour au catalogue</a>

    <div class="lock-notice">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        Connexion s&eacute;curis&eacute;e &mdash; Administrateur unique autoris&eacute;
    </div>

</div>
</body>
</html>
