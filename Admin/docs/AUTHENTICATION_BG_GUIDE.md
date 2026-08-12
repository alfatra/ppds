# 🎨 Panduan Mengganti Background Gambar Login

Background gambar besar di sebelah kanan halaman login (`authentication-bg.jpg`) dapat dengan mudah diganti.

## 📍 Lokasi File Background Saat Ini

**Source File:**
```
resources/images/authentication-bg.jpg
```

**Built File (Production):**
```
public/build/icons/authentication-bg.jpg
```

---

## 🔄 Cara Mengganti Background (3 Langkah Mudah)

### **Langkah 1: Siapkan Gambar Baru Anda**

- **Format:** JPG atau PNG (JPG recommended untuk performa)
- **Ukuran:** Minimal 1920x1080px (Full HD) untuk tampilan terbaik
- **Aspect Ratio:** 16:9 atau lebih lebar (untuk responsif)
- **Nama file:** `authentication-bg.jpg` (gunakan nama yang sama)

**💡 Tips:** Jika ada gambar lain, bisa gunakan nama unik seperti `authentication-bg-custom.jpg`

---

### **Langkah 2: Ganti File**

#### **Opsi A: Replace File Langsung (Tercepat)** ⭐

1. Ganti file di folder `resources/images/`:
   - Hapus file lama: `authentication-bg.jpg`
   - Copy file baru dengan nama yang sama ke folder tersebut

2. Jalankan build:
   ```bash
   npm run build
   ```

3. Refresh browser (Ctrl+F5)
4. **Selesai!** 🎉

---

#### **Opsi B: Gunakan Nama File Baru**

Jika ingin simpan gambar lama dan menambah yang baru:

1. **Upload gambar baru** ke `resources/images/` dengan nama unik, misal:
   - `authentication-bg-custom.jpg`

2. **Edit file** `resources/scss/custom/pages/_authentication.scss`

   Cari baris ini (sekitar line 10):
   ```scss
   .authentication-bg {
       background-image: url("../images/authentication-bg.jpg");
   ```

   Ganti dengan nama file baru Anda:
   ```scss
   .authentication-bg {
       background-image: url("../images/authentication-bg-custom.jpg");
   ```

3. **Build kembali:**
   ```bash
   npm run build
   ```

4. **Refresh browser** (Ctrl+F5)

---

### **Langkah 3: Build & Verifikasi**

```bash
cd C:\xampp\htdocs\ppds\Admin
npm run build
```

Tunggu hingga selesai, lalu:
- Buka halaman login: `http://127.0.0.1:8000/login`
- Refresh dengan **Ctrl+F5** untuk clear cache browser
- Background gambar baru akan tampil! ✨

---

## 💾 File yang Terlibat

```
resources/
├── images/
│   ├── authentication-bg.jpg       ← Ganti ini
│   └── authentication-bg2.jpg      ← Ada alternatif ini
│
└── scss/
    └── custom/pages/
        └── _authentication.scss    ← Edit jika pakai nama file baru
```

---

## 🎯 Alternatif: Gunakan Background yang Ada

Project ini sudah punya 2 background authentication:
- ✅ `authentication-bg.jpg` (sedang digunakan)
- `authentication-bg2.jpg` (alternatif)

Untuk switch ke `authentication-bg2.jpg`:

**Edit** `resources/scss/custom/pages/_authentication.scss`:
```scss
// Dari:
background-image: url("../images/authentication-bg.jpg");

// Menjadi:
background-image: url("../images/authentication-bg2.jpg");
```

Kemudian:
```bash
npm run build
```

---

## 🎨 Tips Desain

- **Ukuran Optimal:** 1920x1080px (Full HD)
- **Format:** JPG untuk file lebih ringkas, PNG jika perlu transparency
- **Overlay Color:** Ada dark overlay (#292626) di atas gambar, jadi gunakan gambar yang kontras
- **Text Readability:** Pastikan form login (di sebelah kiri) tetap terbaca
- **Mobile:** Pada mobile, background tidak ditampilkan (hanya form), jadi fokus pada desktop experience

---

## 📱 Responsive

Background hanya tampil di desktop (screen width > 991px). Di mobile/tablet, hanya form login yang tampil. Jadi tidak perlu khawatir untuk ukuran mobile.

---

## ✅ Checklist

- [ ] Siapkan gambar baru (JPG/PNG, 1920x1080+)
- [ ] Copy ke `resources/images/` dengan nama `authentication-bg.jpg`
- [ ] Jalankan `npm run build`
- [ ] Refresh browser (Ctrl+F5)
- [ ] Verifikasi tampil dengan benar

---

**Pilih opsi yang sesuai dan beri tahu jika butuh bantuan! 😊**
