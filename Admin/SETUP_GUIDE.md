# PPDS Admin - Setup Guide

Panduan lengkap setup project Laravel ini. Semua langkah PHP & Composer sudah selesai.

## ✅ Yang Sudah Selesai

- ✅ PHP extensions enabled (openssl, curl, fileinfo, zip, pdo_mysql)
- ✅ SSL/TLS certificates configured (cacert.pem)
- ✅ Composer dependencies installed
- ✅ Laravel application key generated
- ✅ Database migrations & seeders completed
- ✅ Project structure ready

## 📋 Konfigurasi Saat Ini

**Database:** 
```
Host: 127.0.0.1
Port: 3306
Database: nazox_laravel_v1.1.0
Username: root (no password)
```

**PHP Version:** 8.2.12  
**Laravel Version:** 11.35.0  
**Composer Version:** 2.9.5

## 🚀 Menjalankan Project

### ⚡ QUICK START (Recommended)

1. **Pastikan MySQL & Apache running** di XAMPP Control Panel
2. **Terminal 1** - Jalankan Laravel server:
```bash
cd C:\xampp\htdocs\ppds\Admin
php artisan serve
```

3. **Buka browser:**
   - **`http://127.0.0.1:8000`** — aplikasi Laravel
   
4. **Setiap kali edit CSS/JavaScript:**
```bash
npm run build
```
   Lalu refresh browser (Ctrl+F5)

---

Alternatif: gunakan XAMPP Apache dengan akses `http://localhost/ppds/Admin/public` — tidak perlu `php artisan serve`.

## 🎨 Frontend Assets & Development Workflow

### Setup (First Time Only)

```bash
cd C:\xampp\htdocs\ppds\Admin
npm install
```

### Development Workflow

Template project ini menggunakan **static build approach** — CSS/JS di-compile ke folder `public/build/` dan di-include sebagai file statis.

**Setiap kali Anda mengubah CSS atau JavaScript, jalankan:**

```bash
npm run build
```

Lalu refresh browser (Ctrl+F5) untuk melihat perubahan.

**Full Dev Setup (Optional dengan Hot Reload):**

Jika ingin live reload otomatis saat mengubah asset (tidak perlu refresh manual):

```bash
# Terminal 1 - Watch CSS/JS changes
npm run dev
```

```bash
# Terminal 2 - Run Laravel
php artisan serve
```

Buka: **`http://127.0.0.1:8000`**

⚠️ **Note:** Template saat ini tidak dikonfigurasi untuk Vite HMR (Hot Module Reload). Untuk workflow yang lebih smooth, rebuild file asset setelah perubahan:

```bash
npm run build
```

### Production Build

Sama seperti development, gunakan:

```bash
npm run build
```

Hasil build ada di `public/build/css/` dan `public/build/icons/`

## 📁 Struktur Penting

```
Admin/
├── app/              # Aplikasi code (Controllers, Models, etc)
├── config/           # Configuration files
├── database/         # Migrations & seeders
├── public/           # Entry point & public assets
├── resources/        # Views, CSS, JavaScript
├── routes/           # API & Web routes
├── storage/          # Logs & cache
└── .env             # Environment configuration
```

## 🔧 Troubleshooting

### Database Connection Error
- Pastikan MySQL sudah running di XAMPP
- Sesuaikan `DB_*` di file `Admin/.env` jika perlu

### npm command not found
- Install Node.js dari https://nodejs.org/
- Restart PowerShell/Command Prompt setelah instalasi

### Artisan command tidak bekerja
```bash
cd C:\xampp\htdocs\ppds\Admin
php artisan --version
```

## 📚 Useful Commands

```bash
# Generate new model dengan migration
php artisan make:model ModelName -m

# Generate new controller
php artisan make:controller ControllerName

# View all registered routes
php artisan route:list

# Cache configuration (production)
php artisan config:cache

# Clear all caches
php artisan cache:clear
php artisan config:clear
```

## 📝 Database

Migrasi sudah dijalankan dan membuat tables:
- `users`
- `password_reset_tokens`
- `password_resets`
- `failed_jobs`
- `personal_access_tokens`

Untuk membuat database baru atau reset:
```bash
php artisan migrate:refresh --seed
```

## 🔐 Security

**PENTING untuk Production:**
1. Set `APP_DEBUG=false` di `.env`
2. Ubah `APP_KEY` (sudah di-generate)
3. Set strong database password
4. Update `APP_URL` ke domain production

## 📞 Next Steps

1. Develop your features di folder `app/` dan `resources/`
2. Test dengan `npm run dev` untuk live reload
3. Commit changes ke Git
4. Deploy ke production server

---

**Good luck! Project siap untuk development.** 🎉
