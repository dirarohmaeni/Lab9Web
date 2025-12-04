# 📦 Sistem Barang – Praktikum 9 (Modular PHP)
### Nama: Dira Rohmaeni
### NIM: 312410465
###  Kelas: TI.24.A5
Aplikasi CRUD barang dengan arsitektur modular menggunakan PHP Native + MySQL dan desain UI menggunakan Bootstrap 5 (tema hijau soft).

Project ini dibuat untuk memenuhi Praktikum 9 – Pemrograman Web Universitas Pelita Bangsa.

## ✨ Fitur Aplikasi
Fitur	Deskripsi


🔐 Login	Sistem login sederhana dengan session


🏠 Dashboard	Menu cepat (Data Barang, Tambah Barang, Logout) dengan tampilan card


📄 Daftar Barang	Menampilkan seluruh barang + gambar + statistik total barang & total stok


➕ Tambah Barang	Form upload gambar + input lengkap


✏️ Ubah Barang	Edit seluruh data barang + ganti gambar opsional


🗑️ Hapus Barang	Menghapus data barang dari database


🗂️ Modular Routing	Pemisahan file: header.php, footer.php, view module


🎨 UI modern	Tema hijau pastel, full responsive & center layout


## 📁 Struktur Folder
```
lab9_php_modular/
│
├── index.php                 # Routing utama
├── header.php                # Header + navbar
├── footer.php                # Footer
├── config/
│   └── database.php          # Koneksi MySQL
│
├── assets/
│   ├── img/                  # Penyimpanan gambar barang
│   └── css/
│       └── style.css         # (opsional)
│
├── views/
│   ├── dashboard.php         # Dashboard utama
│   └── modules/
│       ├── auth/
│       │   ├── login.php
│       │   └── logout.php
│       └── user/
│           ├── list.php
│           ├── tambah.php
│           ├── ubah.php
│           └── hapus.php
```


## 🛠️ Instalasi & Setup
1️⃣ Clone / Copy Project

Tempatkan folder ``` lab9_php_modular ``` ke dalam: ``` C:\xampp\htdocs\ ```

2️⃣ Import Database
1. Buka phpMyAdmin
2. Buat database baru: ``` latihan1 ```
3. Import file SQL berikut (buat sendiri jika belum ada):
```
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255)
);

INSERT INTO users (username, password) VALUES
('admin', 'admin'); -- atau gunakan password_hash di server nyata

CREATE TABLE data_barang (
  id_barang INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255),
  kategori VARCHAR(100),
  harga_beli INT,
  harga_jual INT,
  stok INT,
  gambar VARCHAR(200)
);
```
3️⃣ Set Koneksi Database


Edit file: ``` config/database.php ```
Isi seperti:
```
<?php
$conn = mysqli_connect("localhost", "root", "", "latihan1");
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());
?>
```

4️⃣ Jalankan Aplikasi


Buka browser: ``` http://localhost/lab9_php_modular/ ```


🔑 Login Default
```
| Username | Password |
| -------- | -------- |
| admin    | admin    |
```


Pada aplikasi, jika belum login maka otomatis diarahkan ke halaman login.


## 🖼️ Screenshot (contoh)
1. Buka Browser http://localhost/lab9_php_modular/index.php?page=login&msg=loggedout
### 📸 Tangkapan Layar (Screenshot)
✨ Login Page
GANBAR

✨ Dashboard

✨ List Data Barang

✨ Form Tambah Barang

✨ Form Edit Barang

✨ Form Hapus Barang


## 🧩 Routing Sistem

Semua routing diatur pada index.php, contoh:
```
?page=dashboard
?page=list
?page=tambah
?page=ubah&id=3
?page=login
?page=logout
```


## 📌 Catatan Pengembangan

Semua view otomatis masuk ke dalam ``` <div class="page-box"> ``` dari header.php

Jika header/footer hilang, index.php akan menampilkan FALLBACK HEADER/FOOTER untuk debugging

Semua halaman sudah responsive (mobile friendly)


## 👨‍💻 Pembuat

Praktikum 9 – Pemrograman Web
Universitas Pelita Bangsa
Tahun 2025
