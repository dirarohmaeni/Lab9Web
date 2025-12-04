<?php
// views/modules/user/ubah.php - Single-column vertical layout (stacked down)
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 2));
require_once ROOT_PATH . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='text-center text-danger'>ID tidak valid.</div>";
    exit;
}

/* Fetch current item */
$sqlGet = "SELECT id_barang, nama, kategori, gambar, harga_beli, harga_jual, stok FROM data_barang WHERE id_barang = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $sqlGet)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        mysqli_stmt_close($stmt);
        echo "<div class='text-center text-danger'>Data tidak ditemukan.</div>";
        exit;
    }
    mysqli_stmt_bind_result($stmt, $id_barang, $nama, $kategori, $gambar, $harga_beli, $harga_jual, $stok);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    echo "<div class='text-center text-danger'>Gagal menyiapkan query.</div>";
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $harga_beli = (int)($_POST['harga_beli'] ?? 0);
    $harga_jual = (int)($_POST['harga_jual'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $current_gambar = trim($_POST['current_gambar'] ?? '');

    if ($nama === '') $errors[] = 'Nama barang wajib diisi.';

    $newGambar = $current_gambar;
    if (!empty($_FILES['file_gambar']['name'])) {
        $f = $_FILES['file_gambar'];
        $allowed = ['jpg','jpeg','png','gif'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengunggah gambar.';
        } elseif ($f['size'] > 2*1024*1024) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = 'Tipe file tidak diizinkan (jpg/jpeg/png/gif).';
            } else {
                $safe = uniqid('img_', true) . '.' . $ext;
                $destDir = ROOT_PATH . '/assets/img';
                if (!is_dir($destDir)) mkdir($destDir, 0755, true);
                if (!move_uploaded_file($f['tmp_name'], $destDir . '/' . $safe)) {
                    $errors[] = 'Gagal menyimpan file gambar.';
                } else {
                    $newGambar = $safe;
                    if (!empty($current_gambar) && $current_gambar !== $newGambar) {
                        $oldpath = $destDir . '/' . $current_gambar;
                        if (file_exists($oldpath)) @unlink($oldpath);
                    }
                }
            }
        }
    }

    if (empty($errors)) {
        $sqlUpd = "UPDATE data_barang SET nama=?, kategori=?, gambar=?, harga_beli=?, harga_jual=?, stok=? WHERE id_barang=?";
        if ($s2 = mysqli_prepare($conn, $sqlUpd)) {
            mysqli_stmt_bind_param($s2, 'sssiiii', $nama, $kategori, $newGambar, $harga_beli, $harga_jual, $stok, $id);
            if (mysqli_stmt_execute($s2)) {
                mysqli_stmt_close($s2);
                header('Location: index.php?page=list&msg=updated');
                exit;
            } else {
                $errors[] = 'Gagal menyimpan perubahan: ' . mysqli_error($conn);
                mysqli_stmt_close($s2);
            }
        } else {
            $errors[] = 'Gagal menyiapkan query update.';
        }
    }
}

/* Fetch categories for select */
$categories = ['Elektronik','ATK','Makanan'];
$qcat = mysqli_query($conn, "SELECT DISTINCT kategori FROM data_barang WHERE kategori IS NOT NULL AND kategori<>''");
if ($qcat && mysqli_num_rows($qcat) > 0) {
    $categories = [];
    while ($r = mysqli_fetch_assoc($qcat)) $categories[] = $r['kategori'];
    mysqli_free_result($qcat);
}
?>

<!-- Scoped styles: single-column stacked, aesthetic -->
<style>
.vwrap { max-width:720px; margin:28px auto 60px; }
.vcard {
  background:#fff; border-radius:12px; padding:26px; 
  box-shadow: 0 18px 50px rgba(18,120,45,0.06);
  border:1px solid rgba(18,120,45,0.06);
}
.vheader { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:14px; }
.vtitle { font-size:20px; font-weight:700; color:#154e2a; margin:0; }
.btn-back { background:transparent; border:1px solid #cfead0; color:#14582a; border-radius:10px; padding:8px 14px; text-decoration:none; }

/* Form stacked */
.form-stack { display:flex; flex-direction:column; gap:12px; }
.label { font-weight:700; color:#1f6d2f; margin-bottom:6px; }
.input, .select, .file-input {
  width:100%; padding:12px 14px; border-radius:10px; border:1px solid #e6f3e6; font-size:15px;
  background: #fbfff9;
}
.helper { color:#6b8f6b; font-size:13px; }

/* Image preview centered under file input */
.preview-wrap { display:flex; justify-content:center; margin-top:6px; }
.preview-img { max-width:320px; width:100%; border-radius:10px; box-shadow:0 10px 30px rgba(16,142,64,0.06); }

/* Actions centered */
.actions-center { display:flex; gap:12px; justify-content:center; margin-top:18px; }
.btn-cancel { background:transparent; border:1px solid #cfead0; color:#14582a; border-radius:10px; padding:10px 18px; }
.btn-save { background: linear-gradient(180deg,#28a745,#1e7e34); color:#fff; border-radius:10px; padding:10px 22px; border:none; box-shadow:0 10px 28px rgba(31,139,62,0.12); cursor:pointer; }

/* Responsive */
@media (max-width:640px) {
  .vwrap { padding:0 12px; }
  .preview-img { max-width:100%; }
}
</style>

<div class="vwrap">
  <div class="vcard">
    <div class="vheader">
      <h2 class="vtitle">Ubah Barang</h2>
    </div>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
      <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" action="index.php?page=ubah&id=<?= urlencode($id_barang) ?>" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="current_gambar" value="<?= htmlspecialchars($gambar) ?>">

      <div class="form-stack">
        <div>
          <label class="label" for="nama">Nama Barang</label>
          <input id="nama" name="nama" class="input" value="<?= htmlspecialchars($nama) ?>" required>
        </div>

        <div>
          <label class="label" for="kategori">Kategori</label>
          <select id="kategori" name="kategori" class="select">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>" <?= ($kategori === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="label" for="harga_beli">Harga Beli</label>
          <input id="harga_beli" name="harga_beli" type="number" class="input" value="<?= (int)$harga_beli ?>">
        </div>

        <div>
          <label class="label" for="harga_jual">Harga Jual</label>
          <input id="harga_jual" name="harga_jual" type="number" class="input" value="<?= (int)$harga_jual ?>">
        </div>

        <div>
          <label class="label" for="stok">Stok</label>
          <input id="stok" name="stok" type="number" class="input" value="<?= (int)$stok ?>">
        </div>

        <div>
          <label class="label" for="file_gambar">Ganti Gambar <span class="helper">(opsional, maks 2MB)</span></label>
          <div class="file-input">
            <input id="file_gambar" name="file_gambar" type="file" accept=".jpg,.jpeg,.png,.gif" style="border:none;background:transparent;width:100%;">
          </div>
          <div class="preview-wrap">
            <?php if (!empty($gambar) && file_exists(ROOT_PATH . '/assets/img/' . $gambar)): ?>
              <img id="previewImg" src="assets/img/<?= htmlspecialchars($gambar) ?>" class="preview-img" alt="gambar">
            <?php else: ?>
              <img id="previewImg" src="https://via.placeholder.com/320x200?text=No+Image" class="preview-img" alt="no image">
            <?php endif; ?>
          </div>
        </div>

        <div class="actions-center">
          <a href="index.php?page=list" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// client-side file preview and validation
document.addEventListener('DOMContentLoaded', function(){
  const fileInput = document.getElementById('file_gambar');
  const preview = document.getElementById('previewImg');
  if (!fileInput || !preview) return;

  fileInput.addEventListener('change', function(e){
    const f = e.target.files[0];
    if (!f) return;
    const allowed = ['image/jpeg','image/png','image/gif'];
    if (!allowed.includes(f.type)) {
      alert('Tipe file tidak diizinkan. Gunakan JPG/PNG/GIF.');
      fileInput.value = '';
      return;
    }
    if (f.size > 2*1024*1024) {
      alert('Ukuran file terlalu besar. Maks 2MB.');
      fileInput.value = '';
      return;
    }
    const reader = new FileReader();
    reader.onload = function(ev){ preview.src = ev.target.result; };
    reader.readAsDataURL(f);
  });
});
</script>
