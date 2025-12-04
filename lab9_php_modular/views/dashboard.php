<?php
// views/dashboard.php — Dashboard tanpa total barang & stok
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 1));
?>
<!-- Scoped styles: follow tambah.php aesthetic -->
<style>
.dashboard-wrap { max-width:880px; margin:28px auto 60px; }
.dashboard-card {
  border-radius:14px; background:#fff; padding:26px;
  box-shadow: 0 14px 40px rgba(34,139,34,0.04);
  border:1px solid rgba(34,139,34,0.04);
}

.dash-top { margin-bottom:16px; }
.dash-title { margin:0; font-size:28px; font-weight:800; color:#1b6a36; }
.dash-sub { color:#567f5f;margin-top:4px;font-size:15px; }

/* Grid menu cards */
.dash-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:18px;
  margin-top:18px;
}

.dash-item {
  background:linear-gradient(180deg,#fbfff9,#f4fff4);
  border-radius:12px;
  padding:18px;
  border:1px solid #e6f6e6;
  box-shadow:0 8px 22px rgba(18,120,45,0.04);
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  min-height:120px;
}

.dash-item h5 {
  margin:0 0 6px;
  color:#145a2a;
  font-size:16px;
  font-weight:700;
  display:flex;
  gap:8px;
  align-items:center;
}

.dash-item p {
  margin:0;
  color:#556f5b;
  font-size:14px;
}

/* green button */
.btn-save {
  background:linear-gradient(180deg,#28a745,#1e7e34);
  color:#fff;
  border-radius:12px;
  padding:8px 14px;
  border:none;
  text-decoration:none;
  display:inline-block;
  box-shadow:0 8px 20px rgba(30,110,40,0.10);
}

/* Footer small text */
.dash-note {
  text-align:center;
  color:#6b8f6b;
  margin-top:22px;
  font-size:13px;
}
</style>

<div class="dashboard-wrap">
  <div class="dashboard-card">
    
    <div class="dash-top">
      <h1 class="dash-title">✨ Selamat Datang!</h1>
      <p class="dash-sub">Sistem Barang.</p>
    </div>

    <div class="dash-grid">

      <div class="dash-item">
        <div>
          <h5>📦 Data Barang</h5>
          <p>Kelola data barang — lihat daftar, edit, hapus, dan upload gambar.</p>
        </div>
        <div style="margin-top:14px;">
          <a href="index.php?page=list" class="btn-save">Lihat Barang</a>
        </div>
      </div>

      <div class="dash-item">
        <div>
          <h5>➕ Tambah Barang</h5>
          <p>Tambah barang baru ke database.</p>
        </div>
        <div style="margin-top:14px;">
          <a href="index.php?page=tambah" class="btn-save">Tambah Barang</a>
        </div>
      </div>

      <div class="dash-item">
        <div>
          <h5>🔒 Login</h5>
          <p>Masuk untuk fitur akses modul.</p>
        </div>
        <div style="margin-top:14px;">
          <a href="index.php?page=login" class="btn-save">Login</a>
        </div>
      </div>

    </div>
  </div>
</div>
