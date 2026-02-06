# 📝 Panduan Mengganti Logo

## 📍 Lokasi Logo Saat Ini

File logo berada di: `public/build/images/`

**Ada 4 file logo yang digunakan:**

| File | Ukuran | Tema | Lokasi |
|------|--------|------|--------|
| `logo-sm-dark.png` | Small (22px height) | Dark | Sidebar kecil |
| `logo-dark.png` | Large (20px height) | Dark | Sidebar besar |
| `logo-sm-light.png` | Small (22px height) | Light | Sidebar kecil (light mode) |
| `logo-light.png` | Large (20px height) | Light | Sidebar besar (light mode) |

## 🔄 Cara Mengganti Logo

### Opsi 1: Replace File (Tercepat & Termudah) ⭐

1. **Siapkan logo Anda** dalam format PNG (background transparan recommended)
2. **Ukuran yang disarankan:**
   - Small: 80x50px (atau rasio 16:10)
   - Large: 150x50px (atau rasio 3:1)

3. **Ganti file di folder `public/build/images/`:**
   - Ganti `logo-sm-dark.png` dengan logo small Anda (nama file tetap sama)
   - Ganti `logo-dark.png` dengan logo large Anda (nama file tetap sama)
   - Opsional: Ganti light versions jika theme light berbeda

4. **Refresh browser** (Ctrl+F5) - selesai!

---

### Opsi 2: Gunakan Nama File Baru

Jika Anda ingin simpan logo lama + menambah yang baru:

1. **Upload logo baru** ke `public/build/images/` dengan nama unik, misal:
   - `my-logo-sm.png`
   - `my-logo-lg.png`

2. **Edit file** `resources/views/layouts/topbar.blade.php`

   Cari bagian ini:
   ```php
   <a href="index" class="logo logo-dark">
       <span class="logo-sm">
           <img src="{{ URL::asset('build/images/logo-sm-dark.png') }}" alt="logo-sm-dark" height="22">
       </span>
       <span class="logo-lg">
           <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="logo-dark" height="20">
       </span>
   </a>
   ```

   Ganti dengan nama file baru Anda:
   ```php
   <a href="index" class="logo logo-dark">
       <span class="logo-sm">
           <img src="{{ URL::asset('build/images/my-logo-sm.png') }}" alt="my-logo-sm" height="22">
       </span>
       <span class="logo-lg">
           <img src="{{ URL::asset('build/images/my-logo-lg.png') }}" alt="my-logo-lg" height="20">
       </span>
   </a>
   ```

3. **Opsional:** Ulangi untuk versi light:
   ```php
   <a href="index" class="logo logo-light">
       <span class="logo-sm">
           <img src="{{ URL::asset('build/images/my-logo-sm.png') }}" alt="my-logo-sm" height="22">
       </span>
       <span class="logo-lg">
           <img src="{{ URL::asset('build/images/my-logo-lg.png') }}" alt="my-logo-lg" height="20">
       </span>
   </a>
   ```

4. **Refresh browser** (Ctrl+F5)

---

## 💡 Tips

- **Transparent background:** Untuk logo dengan background transparan, gunakan PNG format
- **Aspect ratio:** Jaga rasio tinggi (height) sesuai setting di HTML (22px untuk small, 20px untuk large)
- **Kedua theme:** Light & Dark theme bisa pakai logo yang sama jika ingin
- **Favicon:** Untuk mengubah tab icon, edit file `public/favicon.ico` atau `resources/images/favicon.ico`

---

## 🎯 File yang Dirubah

```
public/build/images/
├── logo-sm-dark.png    ← Ganti ini
├── logo-dark.png       ← Ganti ini
├── logo-sm-light.png   ← Opsional
└── logo-light.png      ← Opsional

resources/views/layouts/
└── topbar.blade.php    ← Edit jika pakai nama file baru
```

---

Pilih opsi yang sesuai dengan kebutuhan Anda! 😊
