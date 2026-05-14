<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

/* “Remember me” auto-login */
if (!empty($_COOKIE['cpsp_remember'])) {
    $token = $_COOKIE['cpsp_remember'];
    if (is_string($token) && strlen($token) === 64) {
        $stmt = $pdo->prepare(
            'SELECT u.id, u.user_type_id, u.username, u.email, ut.name AS type_name
             FROM users u
             INNER JOIN user_types ut ON ut.id = u.user_type_id
             WHERE u.remember_token = :t
             LIMIT 1'
        );
        $stmt->execute([':t' => $token]);
        $row = $stmt->fetch();
        if ($row) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $row['id'];
            $_SESSION['user_type_id'] = (int) $row['user_type_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_type'] = $row['type_name'];
            header('Location: dashboard.php');
            exit;
        }
    }
    setcookie('cpsp_remember', '', ['expires' => time() - 3600, 'path' => app_cookie_path(), 'httponly' => true, 'samesite' => 'Lax']);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$stmt = $pdo->query('SELECT id, name FROM user_types ORDER BY id ASC');
$userTypes = $stmt->fetchAll();

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPSP ePortal – e-Log Book | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-login">
    <main class="login-shell">
        <div class="login-stack">
            <header class="login-header">
                <h1 class="title-green">CPSP ePortal</h1>
                <p class="subtitle">LOGIN TO YOUR ACCOUNT</p>
            </header>

            <div class="logo-block">
                <img src="assets/images/logo.png" alt="CPSP e-Log Book" class="crest-logo" width="120" height="120">
                <p class="elogbook-curve" aria-label="e-Log Book">e-Log Book</p>
            </div>

            <div class="form-wrap">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form id="loginForm" class="login-form" action="login.php" method="post" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-group">
                        <label class="visually-hidden" for="user_type_id">User type</label>
                        <div class="select-wrap">
                            <select name="user_type_id" id="user_type_id" class="form-control form-select" required>
                                <option value="">- Select User Type -</option>
                                <?php foreach ($userTypes as $t): ?>
                                    <option value="<?= (int) $t['id'] ?>"><?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="visually-hidden" for="username">Username</label>
                        <div class="input-icon">
                            <span class="input-icon__i" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="visually-hidden" for="password">Password</label>
                        <div class="input-icon">
                            <span class="input-icon__i" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="current-password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login">Login</button>

                    <div class="remember-row">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember_me" id="remember_me" value="1">
                            <span>Remember me</span>
                        </label>
                    </div>
                </form>

                <section class="forgot-block" aria-labelledby="forgot-heading">
                    <p id="forgot-heading" class="forgot-text">
                        If you have forgot or don't know your password then click on the following button.
                    </p>
                    <button type="button" class="btn btn-forgot" id="btnForgot">Reset / Forgot Password</button>
                </section>
            </div>

            <footer class="site-footer">
                <hr class="footer-rule">
                <p class="copyright">Copyright © 2020 CPSP. All Rights Reserved.</p>
            </footer>
        </div>
    </main>

    <div class="modal" id="forgotModal" role="dialog" aria-modal="true" aria-labelledby="forgotModalTitle" hidden>
        <div class="modal__backdrop" data-close-modal></div>
        <div class="modal__panel">
            <h2 class="modal__title" id="forgotModalTitle">Password help</h2>
            <p class="modal__text">Please contact your CPSP programme office or system administrator to reset your portal password. For security, self-service reset is not enabled in this demo.</p>
            <button type="button" class="btn btn-login" data-close-modal>Close</button>
        </div>
    </div>

    <button type="button" class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <script src="script.js"></script>
</body>
</html>
