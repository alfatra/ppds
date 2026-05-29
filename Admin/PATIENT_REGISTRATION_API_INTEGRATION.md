# Patient Registration API Integration Guide

## Overview
Integrasi API pasien telah berhasil ditambahkan ke sistem PPDS. Fitur ini memungkinkan pengguna untuk mencari dan memilih pasien berdasarkan nomor registrasi atau nama pasien dari API eksternal.

## Apa yang Telah Diubah

### 1. File `.env`
Tambahan konfigurasi API untuk patient registration:
```
API_PATIENT_REGISTRATION_URL=http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2
```

### 2. File `routes/api.php`
Tambahan route baru: `GET /api/pasien`
- Endpoint ini mengambil data pasien dari API eksternal menggunakan HMAC authentication
- Mendukung query parameter `q` untuk pencarian (nomor registrasi atau nama pasien)
- Response format: JSON dengan struktur `{success: bool, data: [], message: string, count: int}`

### 3. File `resources/views/soap_logs/form.blade.php`
Update field patient entry dengan fitur autocomplete:

#### HTML Changes:
- Mengganti simple number input dengan searchable field
- Menambahkan hidden field `patient_id` untuk menyimpan ID pasien yang dipilih
- Menambahkan patient info display section untuk menampilkan informasi pasien yang dipilih

#### JavaScript Changes:
- Menambahkan fungsi `loadPatients()` untuk fetch data pasien dari API
- Menambahkan fungsi `showPatientSuggestions()` untuk menampilkan autocomplete dropdown
- Menambahkan fungsi `selectPatient()` untuk handle pemilihan pasien
- Menambahkan event listeners untuk input, focus, dan click outside

## Cara Menggunakan

### Dari Perspektif User:
1. Saat membuat SOAP Log baru, field "Nomor Registrasi / Nama Pasien" akan muncul
2. Ketik nomor registrasi atau nama pasien untuk mencari
3. Sistem akan otomatis menampilkan daftar pasien yang cocok (max 15 hasil)
4. Klik salah satu pasien dari list untuk memilihnya
5. Informasi pasien akan ditampilkan di bawah field (nama, no registrasi, patient ID)
6. Field `patient_id` akan otomatis terisi dengan ID yang benar untuk disimpan ke database

### Proses Teknis:
1. User mengetik di field `patient_search`
2. JavaScript mengirim request ke `/api/pasien?q=<query>`
3. Backend route `/api/pasien`:
   - Membuat HMAC signature dengan consumer_id dan consumer_password
   - Mengirim request ke API eksternal dengan headers authentication
   - Parse response JSON dan mengembalikan data pasien
4. JavaScript menampilkan hasil sebagai autocomplete dropdown
5. Saat user memilih, `patient_id` diset dengan nilai PatientID dari API

## API Endpoint Details

### Request
```
GET /api/pasien?q=search_term
```

### Query Parameters
- `q` (optional): Kata kunci pencarian (nomor registrasi, nama, atau patient ID)

### Response Format
```json
{
  "success": true,
  "data": [
    {
      "PatientID": "12345",
      "RegistrationNumber": "REG-2024-001",
      "PatientName": "Budi Santoso",
      "DateOfBirth": "1990-05-15",
      ...other fields
    }
  ],
  "count": 1,
  "message": "Success"
}
```

## Expected Patient Data Fields

Sistem ini akan mencari berdasarkan field berikut dari API:
- `PatientID` atau `ID` atau `patient_id` - Untuk menyimpan ke database
- `RegistrationNumber` atau `RegNo` atau `no_registrasi` - Nomor registrasi pasien
- `PatientName` atau `Name` atau `nama` - Nama pasien

**Catatan:** Field names dapat berbeda sesuai format API yang sebenarnya. Jika API menggunakan format berbeda, update fungsi JavaScript di form.blade.php untuk menyesuaikan field mapping.

## Testing Checklist

- [ ] Pastikan API endpoint dapat diakses dari server
- [ ] Pastikan consumer_id dan consumer_password sudah benar
- [ ] Test API authentication dengan menjalankan request manual ke `/api/pasien`
- [ ] Verifikasi HMAC signature generation bekerja dengan benar
- [ ] Test form dengan mencari pasien menggunakan nomor registrasi
- [ ] Test form dengan mencari pasien menggunakan nama
- [ ] Verifikasi patient_id tersimpan dengan benar ke database

## Troubleshooting

### Autocomplete tidak menampilkan hasil
1. Buka browser Console (F12) untuk melihat error messages
2. Check Network tab untuk melihat request/response ke `/api/pasien`
3. Verifikasi HMAC authentication working correctly (check response status)

### Field mapping tidak sesuai
Jika API mengembalikan field names yang berbeda, update mapping di function `selectPatient()` dan `showPatientSuggestions()`:
```javascript
// Update sesuai dengan response structure API
const regNumber = patient.RegistrationNumber || patient.RegNo || patient.no_registrasi || '';
const patientName = patient.PatientName || patient.Name || patient.nama || '';
```

### HMAC signature error
Pastikan API_CONSUMER_ID dan API_CONSUMER_PASSWORD sudah diset dengan benar di .env file

## File Reference

- **Backend Route:** `routes/api.php` (line ~140+)
- **Form View:** `resources/views/soap_logs/form.blade.php` (line 1-30 untuk HTML, line 420-500+ untuk JavaScript)
- **Configuration:** `.env` (API_PATIENT_REGISTRATION_URL, API_CONSUMER_ID, API_CONSUMER_PASSWORD)

## Next Steps (Optional Enhancements)

1. **Caching:** Tambahkan caching untuk patient data agar lebih cepat
   ```php
   return Cache::remember('patients_' . $searchQuery, now()->addHours(1), function() { ... });
   ```

2. **Validation:** Tambahkan server-side validation untuk memastikan patient_id valid

3. **SOAP Log Storage:** Pertimbangkan untuk menyimpan nomor registrasi juga di table SoapLog untuk referensi

4. **Patient Model:** Jika database lokal memiliki table patients, bisa sync dengan API data

## Support

Untuk pertanyaan atau issue, check:
1. Laravel logs di `storage/logs/`
2. Browser console untuk JavaScript errors
3. Network requests di browser developer tools
