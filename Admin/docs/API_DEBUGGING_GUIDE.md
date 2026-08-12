# API Debugging Guide

## Masalah yang Ditemukan
API eksternal mengembalikan status "NOT FOUND" ketika system mencoba mengambil data pasien.

## Test API Secara Manual

### Opsi 1: Buka di Browser
```
http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2
```

Lihat response JSON yang dikembalikan.

### Opsi 2: Gunakan Postman atau cURL
```bash
# Dapatkan timestamp dan generate signature terlebih dahulu
# Timestamp: Unix timestamp saat ini
# Signature: base64_encode(hash_hmac('sha256', timestamp + consumer_id, consumer_password, true))

curl -X GET "http://192.168.10.33/medinfrasapi/workshop/api/registration/base/information/detail2" \
  -H "X-cons-id: 123456" \
  -H "X-timestamp: 1777852098" \
  -H "X-signature: WM/By9JPglmJmjbMZqQSuUnDzM/BkNMZXzdg1o9zP9U=" \
  -H "Accept: application/json"
```

## Yang Perlu Anda Cek

1. **Format URL endpoint:**
   - Apakah endpoint benar? `registration/base/information/detail2`
   - Atau mungkin ada suffix lain yang dibutuhkan?

2. **Parameter pencarian:**
   - Apa nama parameter untuk search? (saat ini kita gunakan `q`, tapi mungkin seharusnya `search`, `regNo`, `patientId`, dll)
   - Apakah perlu POST method bukan GET?

3. **Response format:**
   - Field apa yang dikembalikan? (PatientID, RegistrationNumber, PatientName, dll)
   - Apakah Data selalu JSON string atau object?

## Response yang Kita Terima
```json
{
  "Status": "NOT FOUND",
  "Remarks": "No Data Found",
  "Data": null
}
```

Ini artinya:
- ✅ Koneksi & HMAC signature: OK
- ❌ Endpoint atau parameter: Tidak sesuai

## Langkah Selanjutnya

Tanya tim API/backend yang bikin endpoint:
1. Endpoint URL yang benar untuk fetch patient registration
2. Parameter apa untuk search (nomor registrasi / nama pasien)?
3. Field names yang dikembalikan dalam response
4. Contoh response untuk request yang valid

Atau jika sudah ada dokumentasi API dari tim mereka, share dengan saya!
