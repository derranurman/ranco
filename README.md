# 🏎 Ranco Auto — Panduan Setup XAMPP

## Struktur File

```
ranco-auto/
├── index.html     ← Aplikasi utama (frontend React)
├── api.php        ← Backend API (PHP)
├── config.php     ← Konfigurasi database
└── database.sql   ← Script setup database MySQL
```

---

## Langkah Setup

### 1. Install & Aktifkan XAMPP
- Download XAMPP di https://www.apachefriends.org
- Buka **XAMPP Control Panel**
- Klik **Start** pada **Apache** dan **MySQL**

---

### 2. Letakkan File
Salin seluruh folder **ranco-auto** ke:
```
C:\xampp\htdocs\ranco-auto\     (Windows)
/opt/lampp/htdocs/ranco-auto/   (Linux/Mac)
```

---

### 3. Buat Database
**Cara A — phpMyAdmin (Mudah):**
1. Buka browser → http://localhost/phpmyadmin
2. Klik tab **SQL**
3. Copy-paste isi file `database.sql` → klik **Go**

**Cara B — Import File:**
1. Buka phpMyAdmin → klik **Import**
2. Pilih file `database.sql` → klik **Go**

---

### 4. Sesuaikan Konfigurasi (jika perlu)
Buka `config.php`, ubah sesuai pengaturan XAMPP Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // default XAMPP
define('DB_PASS', '');         // default kosong
define('DB_NAME', 'ranco_auto');
```

> ⚠️ Jika XAMPP MySQL Anda pakai password, isi `DB_PASS`.

---

### 5. Buka Aplikasi
Buka browser → **http://localhost/ranco-auto/**

---

## Akun Default

| Username | Password   | Role      |
|----------|------------|-----------|
| admin    | admin123   | Admin     |
| dayu     | dayu123    | Host Live |
| nia      | nia123     | Host Live |
| amanda   | amanda123  | Host Live |
| packing  | pack123    | Packing   |

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Halaman putih / tidak muncul | Pastikan Apache sudah Start di XAMPP |
| "Gagal terhubung ke database" | Pastikan MySQL sudah Start, dan database `ranco_auto` sudah dibuat |
| Data tidak tersimpan | Buka http://localhost/ranco-auto/api.php — cek error yang muncul |
| Koneksi ditolak | Cek `config.php`, pastikan DB_USER dan DB_PASS benar |

---

## Cara Kerja

```
Browser (React)  ──GET──►  api.php  ──SELECT──►  MySQL
                 ◄──JSON──          ◄──Data──

Browser (React)  ──POST──►  api.php  ──INSERT/UPDATE──►  MySQL
                 ◄──OK──             ◄──Saved──
```

Setiap perubahan data (tambah order, ubah stok, dll) langsung tersimpan ke MySQL secara otomatis. Data aman meski browser ditutup.

---

## Reset Data

Untuk mengembalikan ke data awal, jalankan di phpMyAdmin:
```sql
DELETE FROM ranco_auto.app_data;
```
Lalu refresh aplikasi — data awal akan terisi otomatis.
