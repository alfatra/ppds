# Testing Patient Registration API Integration

## Apa yang Sudah Diubah (v2)

### 1. Route API (`routes/api.php`)
- ✅ Updated `/api/pasien` endpoint dengan parameter yang **benar**:
  - `registrationNo` (nomor registrasi)
  - `medicalNo` (nomor medis)
  - `paramedicCode` (kode paramedis)
  - `departmentID` (ID departemen)
  - `periodeRegistrationDate` (periode tanggal registrasi)

- ✅ Ditambahkan endpoint baru `/api/registrasi/{registrationNo}`:
  - Untuk fetch detail registrasi berdasarkan nomor registrasi
  - Menggunakan pattern dari CodeIgniter Model_API_Medinfras

- ✅ API response parsing:
  - Decode field `Data` yang berupa JSON string (sesuai API format)
  - Handle berbagai format response

### 2. Form View (`resources/views/soap_logs/form.blade.php`)
- ✅ Updated JavaScript untuk menggunakan parameter `registrationNo`
- ✅ Improved patient suggestions dengan lebih banyak field info:
  - Registration Number
  - Medical Number
  - Patient Name
  - Date of Birth
  - Gender
  - Address

- ✅ Better field mapping untuk handle berbagai nama field dari API

## Cara Test

### Test 1: Manual Test ke API
Buka browser atau Postman dan test endpoint:

```
http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2?registrationNo=REG-2024-001&periodeRegistrationDate=2024-01-01
```

**Catatan:** Gunakan authentication headers:
- `X-cons-id: 123456`
- `X-timestamp: [current-unix-timestamp]`
- `X-signature: [hmac-sha256-signature]`

### Test 2: Test via Laravel Route
1. Buka browser atau Postman
2. Test endpoint: `http://localhost/api/pasien?registrationNo=REG-2024-001`
3. Expected response:
```json
{
  "success": true,
  "data": [
    {
      "RegistrationNo": "REG-2024-001",
      "PatientName": "John Doe",
      "MedicalNo": "12345",
      "DateOfBirth": "1990-01-01",
      ...
    }
  ],
  "count": 1
}
```

### Test 3: Test di Form SOAP Log
1. Buka halaman Create SOAP Log
2. Field "Nomor Registrasi / Nama Pasien" seharusnya bisa:
   - Terima input text
   - Saat user mengetik nomor registrasi, tampilkan autocomplete dropdown
   - Klik pasien dari dropdown, akan otomatis fill patient info

### Test 4: Check Browser Console
Buka DevTools (F12) → Console tab:
```javascript
// Lihat log dari API call
"Fetching from: /api/pasien?registrationNo=REG-2024-001&periodeRegistrationDate=2024-05-04"
"Response status: 200"
"Patient API Response: {...}"
```

## Field Mapping

Sistem ini mencari field dengan nama-nama berikut (dalam urutan prioritas):

**Registration Number:**
- `RegistrationNo` → `RegistrationNumber` → `RegNo` → `no_registrasi`

**Medical Number:**
- `MedicalNo` → `MedicalNumber` → `NoMedis`

**Patient Name:**
- `PatientName` → `Name` → `nama` → `NamePatient`

**Patient ID:**
- `PatientID` → `ID` → `patient_id` → `MedicalNo` → `RegistrationNo`

**Tambahan (untuk display):**
- `DateOfBirth`, `DOB`, `TanggalLahir`
- `Gender`, `Jenis_Kelamin`
- `Address`, `Alamat`

## Troubleshooting

### Autocomplete tidak menampilkan hasil
1. **Check parameter yang dikirim:**
   - Console F12 → lihat URL yang di-fetch
   - Pastikan format parameter sesuai API requirement

2. **Check API response:**
   - Network tab → lihat response dari `/api/pasien`
   - Lihat format field dari API response

3. **Check field mapping:**
   - Jika API return field dengan nama berbeda, perlu update mapping di JavaScript
   - Lihat console log untuk debug info

### API return "NOT FOUND"
- Berarti parameter yang dikirim tidak sesuai
- Cek di logs: `storage/logs/laravel.log`
- Test parameter dengan CodeIgniter model `getRegistrationDetail()` untuk lihat format yang benar

### Decode error
- API response `Data` field tidak bisa di-decode sebagai JSON
- Check format response dari API
- Mungkin sudah dalam format array, tidak perlu decode

## Endpoints Available

### 1. Search Patient Registration
```
GET /api/pasien?registrationNo={nomor}&periodeRegistrationDate={date}
GET /api/pasien?medicalNo={nomor}
GET /api/pasien?paramedicCode={kode}
```

### 2. Get Registration Detail
```
GET /api/registrasi/{registrationNo}
```
Contoh: `GET /api/registrasi/REG-2024-001`

### 3. Existing Endpoints
```
GET /api/dokter          - Get list of doctors
GET /api/diagnosa        - Get list of diagnosis
GET /api/diagnosa/search - Search diagnosis
```

## Parameter Details

| Parameter | Deskripsi | Wajib? | Contoh |
|-----------|-----------|--------|--------|
| registrationNo | Nomor registrasi pasien | No | REG-2024-001 |
| medicalNo | Nomor medis pasien | No | 12345 |
| paramedicCode | Kode paramedis/dokter | No | HAI0001 |
| departmentID | ID departemen | No | OUTPATIENT |
| periodeRegistrationDate | Periode tanggal registrasi | No | 2024-05-04 |

## Debugging Tips

1. **Enable debug logging di Laravel:**
   ```php
   // Di .env
   APP_DEBUG=true
   LOG_LEVEL=debug
   ```

2. **Check logs di:** `storage/logs/laravel.log`
   - Cari entry dengan "Patient Registration API Request"
   - Lihat parameter yang dikirim
   - Lihat response status dan body

3. **Test di Browser DevTools (F12):**
   ```javascript
   // Manual test
   fetch('/api/pasien?registrationNo=REG-2024-001')
     .then(r => r.json())
     .then(d => console.log(d))
   ```

## Next Steps

1. **Verify dengan nomor registrasi yang valid**
   - Tanya ke sistem lain atau database
   - Test dengan nomor yang seharusnya ada data

2. **Jika masih error, share response dari API**
   - Copy response dari logs atau Postman
   - Sehingga saya bisa update field mapping

3. **Optional: UI Improvement**
   - Add filter selector (Registrasi No / Medis No / Kode Dokter)
   - Add caching untuk performa lebih baik
   - Add patient history/recent patients
