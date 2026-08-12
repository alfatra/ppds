# 🗄️ Panduan Integrasi Database PPDS

Database project Laravel telah dikonfigurasi untuk menggunakan database **PPDS** Anda.

## ✅ Konfigurasi Database

File `.env` sudah diupdate dengan setting berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PPDS
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🚀 Langkah Setup Database (3 Tahap)

### **Tahap 1: Jalankan MySQL di XAMPP**

1. **Buka XAMPP Control Panel**
   - Windows: `C:\xampp\xampp-control.exe`

2. **Klik tombol "Start" di sebelah MySQL**
   - Tunggu sampai statusnya berubah menjadi "Running" (hijau)
   - Port yang digunakan: **3306**

3. **Verifikasi MySQL berjalan**
   - Buka command prompt atau PowerShell
   - Jalankan: `mysql -u root`
   - Jika konek, ketik `exit` untuk keluar

---

### **Tahap 2: Buat Database PPDS (Jika Belum Ada)**

**❓ Jika database PPDS sudah ada**, skip ke Tahap 3.

**❌ Jika belum ada database PPDS**, jalankan:

**Opsi A - Gunakan MySQL Command Line:**
```bash
mysql -u root
```

Kemudian di MySQL prompt:
```sql
CREATE DATABASE PPDS;
EXIT;
```

**Opsi B - Gunakan phpMyAdmin (GUI):**
1. Buka browser: `http://localhost/phpmyadmin`
2. Login (default: username `root`, no password)
3. Klik menu "Databases"
4. Di bagian "Create new database", ketik: `PPDS`
5. Klik tombol "Create"

---

### **Tahap 3: Jalankan Laravel Migrations**

Setelah MySQL berjalan dan database PPDS ada, jalankan:

```bash
cd C:\xampp\htdocs\ppds\Admin

# Jalankan migrasi untuk membuat tabel-tabel
php artisan migrate --force
```

**Output yang diharapkan:**
```
INFO  Preparing database.

Creating migration table ..................... DONE

INFO  Running migrations.

2014_10_12_000000_create_users_table ........ DONE
2014_10_12_100000_create_password_reset_tokens_table .... DONE
2014_10_12_100000_create_password_resets_table ......... DONE
2019_08_19_000000_create_failed_jobs_table ........... DONE
2019_12_14_000001_create_personal_access_tokens_table .... DONE
```

---

## 📊 Tabel yang Dibuat

Migrasi akan membuat tabel-tabel berikut di database PPDS:

| Tabel | Fungsi |
|-------|--------|
| `migrations` | Tracking migrasi yang sudah dijalankan |
| `users` | Data pengguna aplikasi |
| `password_reset_tokens` | Token untuk reset password |
| `password_resets` | (Legacy) Untuk reset password lama |
| `failed_jobs` | Queue jobs yang gagal |
| `personal_access_tokens` | API tokens untuk Sanctum (auth) |

---

## 🔧 Database Credentials

```
Host: 127.0.0.1
Port: 3306
Database: PPDS
Username: root
Password: (kosong)
```

---

## 📱 Verifikasi Koneksi

Setelah migrasi berhasil, verifikasi koneksi database dengan:

```bash
php artisan tinker
```

Di Tinker prompt, jalankan:
```php
DB::connection()->getPDO();
```

Jika berhasil, akan menampilkan object PDO tanpa error.

Ketik `exit()` untuk keluar dari Tinker.

---

## 🆘 Troubleshooting

### ❌ "No connection could be made"
- ✅ Pastikan MySQL sudah di-start di XAMPP Control Panel
- ✅ Pastikan port 3306 tidak terblokir
- ✅ Cek file `.env` sudah benar dengan `DB_DATABASE=PPDS`

### ❌ "Database 'PPDS' doesn't exist"
- ✅ Buat database PPDS (lihat Tahap 2)
- ✅ Atau jalankan: `mysql -u root -e "CREATE DATABASE PPDS;"`

### ❌ "Access denied for user 'root'"
- ✅ Pastikan password di `.env` kosong: `DB_PASSWORD=`
- ✅ Jika ada password, update `.env` dengan password yang benar

### ❌ Error saat migrasi
- ✅ Jika database sudah punya tabel, gunakan: `php artisan migrate:refresh --force`
- ⚠️ Warning: perintah ini akan menghapus semua tabel dan membuat ulang!

---

## 📝 Urutan Langkah Singkat

```
1. Buka XAMPP, start MySQL
2. Buat database PPDS (jika belum ada)
3. Jalankan: php artisan migrate --force
4. Selesai! ✨
```

---

## 🔄 Jika Ingin Reset Database

Jika sudah ada data dan ingin reset ulang dari awal:

```bash
php artisan migrate:refresh --force
```

⚠️ **Perintah ini akan:**
- Rollback semua migrasi
- Menghapus semua tabel
- Buat ulang tabel kosong

---

## ✨ Next Steps

Setelah database terintegrasi:

1. ✅ Jalankan `php artisan serve` untuk start aplikasi
2. ✅ Akses `http://127.0.0.1:8000` di browser
3. ✅ Halaman login siap digunakan
4. ✅ Database PPDS siap menerima data

---

**Pastikan MySQL berjalan sebelum jalankan migrasi!** 🚀

Butuh bantuan? Beri tahu error yang muncul! 😊
