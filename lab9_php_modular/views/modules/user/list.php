<?php
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Statistik
$stats = ['total_barang'=>0,'total_stok'=>0];
$q = mysqli_query($conn, "SELECT COUNT(*) AS cnt, COALESCE(SUM(stok),0) AS total_stok FROM data_barang");
if ($q) {
    $r = mysqli_fetch_assoc($q);
    $stats['total_barang'] = $r['cnt'];
    $stats['total_stok'] = $r['total_stok'];
    mysqli_free_result($q);
}

// Data barang
$result = mysqli_query($conn, "SELECT * FROM data_barang ORDER BY id_barang DESC");
?>

<style>
/* wrapper inside the page-box so the content is centered and card-like */
.page-container {
    width: 100%;
    padding: 8px 0 0 0;
    background: transparent;
}

/* the white centered card */
.list-wrapper-box {
    background: #ffffff;
    padding: 26px;
    border-radius: 16px;
    box-shadow: 0 16px 40px rgba(20, 80, 40, 0.06);
    border: 1px solid #e6f3e6;
    max-width: 1100px;
    margin: 0 auto; /* center */
}

/* Header */
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.list-title {
    font-size: 28px;
    font-weight: 800;
    color: #1a6b34;
    margin: 0;
}

/* Tombol Tambah */
.btn-add {
    background: linear-gradient(180deg,#28a745,#1e7e34);
    color: #fff !important;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 15px;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(30,110,40,0.15);
}
.btn-add:hover { background: #1e7e34; }

/* Stats */
.stats-row {
    display:flex;
    gap:16px;
    margin-bottom:18px;
    flex-wrap:wrap;
}
.stat-card {
    min-width:160px;
    background:linear-gradient(180deg,#f7fff7,#ecfff0);
    border:1px solid #d8f3df;
    padding:16px;
    border-radius:12px;
    text-align:center;
}
.stat-number { font-size:26px; font-weight:700; color:#1f7a2f; }
.stat-label { color:#487a52; font-size:13px; }

/* Table container */
.table-dashboard {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(22,100,40,0.05);
}

/* ensure center align, but make columns not too tight */
.table-dashboard th,
.table-dashboard td {
    text-align: center !important;
    vertical-align: middle !important;
    white-space: nowrap;
}

/* Image */
.table-dashboard td img {
    width: 70px !important;
    height:70px;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 6px 14px rgba(0,0,0,0.06);
}

/* ACTION buttons (centered, consistent size) */
.action-buttons {
    display:inline-flex;
    flex-direction:column;
    gap:10px;
    align-items:center;
}
@media (min-width:700px) {
    .action-buttons { flex-direction:column; } /* keep vertical on desktop */
}

/* button look */
.action-btn {
    display:inline-block;
    padding:10px 28px;
    border-radius:10px;
    color:#fff !important;
    font-weight:700;
    text-decoration:none;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    transition: transform .12s ease, box-shadow .12s ease;
}
.action-edit { background: linear-gradient(180deg,#28a745,#1e7e34); }
.action-delete { background: linear-gradient(180deg,#dc3545,#b92030); }
.action-btn:hover { transform: translateY(-3px); box-shadow:0 14px 28px rgba(0,0,0,0.10); }

/* empty state */
.table-dashboard .no-data { padding:24px; text-align:center; }

/* ensure table scrolls on small screens */
@media (max-width:700px){
    .table-dashboard th, .table-dashboard td { white-space:normal; font-size:14px; padding:10px; }
    .action-buttons { flex-direction:row; gap:8px; }
    .action-btn { padding:8px 12px; }
}
</style>

<div class="page-container">
  <div class="list-wrapper-box">

    <!-- HEADER -->
    <div class="list-header">
        <h1 class="list-title">Daftar Barang</h1>
        <a href="index.php?page=tambah" class="btn-add">➕ Tambah Barang</a>
    </div>

    <!-- STATISTIK -->
    <div class="stats-row mb-3">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_barang']) ?></div>
            <div class="stat-label">Total Barang</div>
        </div>

        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['total_stok']) ?></div>
            <div class="stat-label">Total Stok</div>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-responsive table-dashboard">
        <table class="table table-bordered table-striped bg-white align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px">ID</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th style="width:150px">Harga</th>
                    <th style="width:90px">Stok</th>
                    <th style="width:120px">Gambar</th>
                    <th style="width:180px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($b = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['id_barang']) ?></td>
                            <td><?= htmlspecialchars($b['nama']) ?></td>
                            <td><?= htmlspecialchars($b['kategori']) ?></td>
                            <td>Rp <?= number_format($b['harga_jual']) ?></td>
                            <td><?= (int)$b['stok'] ?></td>

                            <td>
                                <?php if (!empty($b['gambar']) && file_exists(ROOT_PATH . '/assets/img/' . $b['gambar'])): ?>
                                    <img src="assets/img/<?= htmlspecialchars($b['gambar']) ?>" alt="gambar">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <div class="action-buttons" role="group" aria-label="aksi">
                                  <a class="action-btn action-edit" href="index.php?page=ubah&id=<?= urlencode($b['id_barang']) ?>">
                                    ✏️ Edit
                                  </a>

                                  <a class="action-btn action-delete" href="index.php?page=hapus&id=<?= urlencode($b['id_barang']) ?>"
                                     onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                    🗑️ Hapus
                                  </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

  </div><!-- end .list-wrapper-box -->
</div><!-- end .page-container -->
