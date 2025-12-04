<?php
// views/modules/auth/login.php
// Login view + handler — styled to match tambah/ubah pages (centered card, green theme)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/database.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Isi username & password.';
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $id, $db_user, $db_hash);
            if (mysqli_stmt_fetch($stmt)) {
                if ((function_exists('password_verify') && password_verify($password, $db_hash)) || $password === $db_hash) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $db_user;
                    mysqli_stmt_close($stmt);
                    header('Location: index.php?page=dashboard');
                    exit;
                } else {
                    $errors[] = 'Username atau password salah.';
                }
            } else {
                $errors[] = 'Username atau password salah.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = 'Gagal memproses login.';
        }
    }
}
?>

<!-- Scoped styles to match tambah/ubah -->
<style>
.login-wrap { max-width: 520px; margin:36px auto 80px; }
.login-card {
  background:#fff; border-radius:12px; padding:26px;
  box-shadow: 0 18px 50px rgba(18,120,45,0.04);
  border:1px solid rgba(18,120,45,0.04);
}

/* header */
.login-header { text-align:center; margin-bottom:12px; }
.login-icon {
  width:150px;
  height:150px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  background: linear-gradient(135deg, #b9f5c5, #2ebf5b);
  box-shadow: 0 18px 40px rgba(30, 120, 52, 0.15);
  margin:0 auto 18px auto;
  font-size:70px; /* daun besar */
}

.login-title { font-size:20px; font-weight:700; color:#154e2a; margin:0 0 6px; }
.login-sub { color:#6b8f6b; margin:0; }

/* form controls (match tambah/ubah) */
.label { display:block; margin-bottom:6px; font-weight:700; color:#1f6d2f; }
.input-lg {
  border-radius:12px; padding:12px 14px; font-size:15px;
  border:1px solid #e6f3e6; width:100%; background:#fbfff9;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
}

/* file/others not used here */
.btn-save {
  background: linear-gradient(180deg,#28a745,#1e7e34); color:#fff; border-radius:12px;
  padding:10px 20px; border:none; box-shadow:0 8px 26px rgba(30,110,40,0.12);
}

/* subtle error */
.alert { margin-top:10px; }

/* small helpers */
.form-actions { display:flex; gap:12px; justify-content:center; margin-top:18px; }
.helper { font-size:13px; color:#6b8f6b; text-align:center; margin-top:10px; }
</style>

<div class="login-wrap">
  <div class="login-card">
    <div class="login-header">
      <div class="login-icon">🌿</div>
      <h3 class="login-title">Masuk</h3>
      <p class="login-sub">Silakan login untuk melanjutkan</p>
    </div>

    <?php if (!empty($errors)): foreach ($errors as $e): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; endif; ?>

    <form method="post" action="index.php?page=login" novalidate>
      <div class="mb-3">
        <label class="label" for="username">Username</label>
        <input id="username" name="username" class="input-lg" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
      </div>

      <div class="mb-3">
        <label class="label" for="password">Password</label>
        <input id="password" name="password" type="password" class="input-lg" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-save">Login</button>
      </div>
    </form>
  </div>
</div>
