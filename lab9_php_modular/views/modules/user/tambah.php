<?php
// views/modules/user/tambah.php - Aesthetic centered add form (green theme)
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$errors = [];
$old = [
  'nama' => '',
  'kategori' => '',
  'harga_beli' => '',
  'harga_jual' => '',
  'stok' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nama'] = trim($_POST['nama'] ?? '');
    $old['kategori'] = trim($_POST['kategori'] ?? '');
    $old['harga_beli'] = (int)($_POST['harga_beli'] ?? 0);
    $old['harga_jual'] = (int)($_POST['harga_jual'] ?? 0);
    $old['stok'] = (int)($_POST['stok'] ?? 0);

    if ($old['nama'] === '') $errors[] = 'Nama barang wajib diisi.';

    // handle upload
    $gambarName = null;
    if (!empty($_FILES['file_gambar']['name'])) {
        $f = $_FILES['file_gambar'];
        $allowed = ['jpg','jpeg','png','gif'];
        if ($f['error'] !== UPLOAD_ERR_OK) $errors[] = 'Gagal mengunggah gambar.';
        elseif ($f['size'] > 2*1024*1024) $errors[] = 'Ukuran gambar maksimal 2MB.';
        else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) $errors[] = 'Tipe file tidak diizinkan (jpg/jpeg/png/gif).';
            else {
                $safe = uniqid('img_', true) . '.' . $ext;
                $destDir = ROOT_PATH . '/assets/img';
                if (!is_dir($destDir)) mkdir($destDir, 0755, true);
                if (!move_uploaded_file($f['tmp_name'], $destDir . '/' . $safe)) $errors[] = 'Gagal menyimpan file gambar.';
                else $gambarName = $safe;
            }
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO data_barang (nama, kategori, gambar, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'sssiii', $old['nama'], $old['kategori'], $gambarName, $old['harga_beli'], $old['harga_jual'], $old['stok']);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: index.php?page=list&msg=added');
                exit;
            } else {
                $errors[] = 'Gagal menyimpan: ' . mysqli_error($conn);
                mysqli_stmt_close($stmt);
            }
        } else $errors[] = 'Gagal menyiapkan query.';
    }
}

// fetch distinct categories from DB (to populate select). fallback to some defaults
$categories = ['Elektronik','ATK','Makanan'];
$q = mysqli_query($conn, "SELECT DISTINCT kategori FROM data_barang WHERE kategori IS NOT NULL AND kategori<>''");
if ($q && mysqli_num_rows($q) > 0) {
    $categories = [];
    while ($r = mysqli_fetch_assoc($q)) $categories[] = $r['kategori'];
    mysqli_free_result($q);
}
?>

<!-- Scoped styles -->
<style>
.add-wrap { max-width:760px; margin:30px auto 60px; }
.add-card {
  border-radius:14px; background:#fff; padding:24px;
  box-shadow: 0 14px 40px rgba(34,139,34,0.06); border:1px solid rgba(34,139,34,0.06);
}
.hint { color:#6b8f6b; font-size:14px; margin-bottom:18px; }
.input-lg {
  border-radius:12px; padding:14px 16px; font-size:15px;
  border:1px solid #e6f3e6;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
}
.select-lg { border-radius:12px; padding:12px 14px; border:1px solid #e6f3e6; }
.file-box { border-radius:12px; padding:8px 12px; border:1px dashed #dfefe0; background:#fbfff9; }
.btn-save {
  background: linear-gradient(180deg,#28a745,#1e7e34); color:#fff; border-radius:12px;
  padding:10px 20px; border:none; box-shadow:0 8px 26px rgba(30,110,40,0.12);
}
.btn-cancel { background:transparent; border:1px solid #cfead0; color:#14582a; border-radius:10px; padding:8px 16px; }
.label { font-weight:700; color:#1f6d2f; margin-bottom:6px; display:block; }
.form-row { display:flex; gap:14px; flex-wrap:wrap; }
.col-2 { flex:1 1 48%; min-width:180px; }
.col-1 { flex:1 1 100%; }
.note { font-size:13px; color:#6b8f6b; margin-top:6px; }
.alert { margin-top:8px; }
@media (max-width:720px) {
  .form-row { flex-direction:column; }
  .col-2 { flex-basis:100%; }
}
</style>

<div class="add-wrap">
  <div class="add-card">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
      <h2 style="margin:0; color:#18602a;">Tambah Barang</h2>
    </div>

    <p class="hint">Isi data barang dengan lengkap. Gunakan gambar ukuran maksimal 2MB.</p>

    <?php if (!empty($errors)): foreach ($errors as $e): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; endif; ?>

    <form method="post" action="index.php?page=tambah" enctype="multipart/form-data" class="mt-3" novalidate>
      <div class="mb-3">
        <label class="label" for="nama">Nama Barang</label>
        <input id="nama" name="nama" class="input-lg" placeholder="Contoh: Mouse Logitech" value="<?= htmlspecialchars($old['nama']) ?>" required>
      </div>

      <div class="form-row">
        <div class="col-2 mb-3">
          <label class="label" for="kategori">Kategori</label>
          <select id="kategori" name="kategori" class="select-lg">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>" <?= ($old['kategori'] === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-2 mb-3">
          <label class="label" for="harga_beli">Harga Beli</label>
          <input id="harga_beli" name="harga_beli" type="number" class="input-lg" placeholder="0" value="<?= htmlspecialchars($old['harga_beli']) ?>">
        </div>

        <div class="col-2 mb-3">
          <label class="label" for="harga_jual">Harga Jual</label>
          <input id="harga_jual" name="harga_jual" type="number" class="input-lg" placeholder="0" value="<?= htmlspecialchars($old['harga_jual']) ?>">
        </div>

        <div class="col-2 mb-3">
          <label class="label" for="stok">Stok</label>
          <input id="stok" name="stok" type="number" class="input-lg" placeholder="0" value="<?= htmlspecialchars($old['stok']) ?>">
        </div>
      </div>

      <div class="mb-3">
        <label class="label" for="file_gambar">File Gambar</label>
        <div class="file-box">
          <input id="file_gambar" name="file_gambar" type="file" accept=".jpg,.jpeg,.png,.gif" style="border:none;background:transparent;">
        </div>
        <div class="note">Tipe: JPG/PNG/GIF. Maks 2MB.</div>
      </div>

      <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:10px;">
        <a href="index.php?page=list" class="btn-cancel">Batal</a>
        <button class="btn-save" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Optional: small JS to show selected file name (enhancement) -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  const inp = document.getElementById('file_gambar');
  if (!inp) return;
  inp.addEventListener('change', function(){
    if (!inp.files || !inp.files[0]) return;
    // (optional) you can show file name, or preview - keep simple
  });
});
</script>
