<?php
// config/database.php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'latihan1'; // sesuaikan db mu

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) die('Koneksi DB gagal: ' . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8mb4');
