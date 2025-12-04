<?php
// views/modules/user/hapus.php
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php?page=list&err=invalid_id');
    exit;
}

// fetch gambar nama untuk hapus file
$sqlGet = "SELECT gambar FROM data_barang WHERE id_barang = ? LIMIT 1";
$gambar = null;
if ($s = mysqli_prepare($conn, $sqlGet)) {
    mysqli_stmt_bind_param($s, 'i', $id);
    mysqli_stmt_execute($s);
    mysqli_stmt_bind_result($s, $gambar);
    mysqli_stmt_fetch($s);
    mysqli_stmt_close($s);
}

// delete record
$sql = "DELETE FROM data_barang WHERE id_barang = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        if ($gambar) {
            $file = ROOT_PATH . '/assets/img/' . $gambar;
            if (file_exists($file)) @unlink($file);
        }
        header('Location: index.php?page=list&msg=deleted');
        exit;
    } else {
        mysqli_stmt_close($stmt);
        header('Location: index.php?page=list&err=delete_failed');
        exit;
    }
} else {
    header('Location: index.php?page=list&err=stmt_failed');
    exit;
}
