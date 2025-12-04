<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Sistem Barang</title>

  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --g1:#e9fff0;
      --g2:#bffad6;
      --g3:#39b85a;
      --g4:#1f8c3d;
    }

    /* Body + spacing for fixed navbar */
    body{
      margin:0;
      font-family:"Segoe UI",sans-serif;
      background: linear-gradient(180deg,#f6fff6,#ffffff 60%);
      /* leave space for fixed navbar so content won't be hidden */
      padding-top:80px;
    }

    /* NAVBAR fixed top (full-width) */
    .topbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: linear-gradient(90deg,var(--g1),var(--g2));
      box-shadow: 0 6px 28px rgba(6,55,22,0.06);
      z-index: 1030;
    }
    .topbar .nav-inner { padding: 10px 0; }

    .brand-strong {
      font-weight:700;
      color:#0b4f22;
      display:flex;
      gap:8px;
      align-items:center;
      font-size:18px;
    }

    .nav-menu .nav-link {
      color:#083913 !important;
      font-weight:600;
      padding:8px 14px;
      border-radius:8px;
      transition:.15s;
    }
    .nav-menu .nav-link:hover {
      background: rgba(255,255,255,0.35);
      color:#083913 !important;
    }
    .nav-menu .logout-link { font-weight:700 !important; color:#083913 !important; }

    /* Page box (centered content) */
    .page-box {
      max-width:1120px;
      margin:36px auto;
      background:#fff;
      padding:28px;
      border-radius:14px;
      box-shadow: 0 16px 40px rgba(17,95,39,0.03);
    }

    /* ensure responsive spacing */
    @media (max-width:576px){
      .brand-strong { font-size:16px; }
      .page-box { margin:18px; padding:18px; }
      body { padding-top:96px; } /* extra space if navbar toggles taller */
    }
  </style>
</head>
<body>

<!-- NAVBAR full-width but content centered by .container -->
<header class="topbar">
  <div class="nav-inner">
    <div class="container">
      <nav class="navbar navbar-expand-lg p-0">
        <a class="navbar-brand brand-strong" href="index.php?page=dashboard">
          💚 <span>Sistem Barang</span>
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
          <ul class="navbar-nav ms-auto nav-menu">
            <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=list">Data Barang</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=tambah">Tambah</a></li>

            <?php if (!empty($_SESSION['user_id'])): ?>
              <li class="nav-item"><a class="nav-link logout-link" href="index.php?page=logout">Logout</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="index.php?page=login">Login</a></li>
            <?php endif; ?>

          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>

<!-- PAGE CONTENT wrapper (views will go inside this) -->
<div class="page-box">
