# Database Check & Fix

Untuk fitur dropdown Jurusan → Kelas bekerja, perlu dipastikan:

## 1. Check Database Structure

Jalankan query di phpMyAdmin untuk cek struktur `kelas` table:

```sql
DESC kelas;
```

**Harusnya output:**
- `id_kelas` (INT, PRIMARY KEY)
- `nama_kelas` (VARCHAR)
- `jurusan_id` (INT, FOREIGN KEY ke jurusan.id_jurusan)

## 2. Check Data di Kelas Table

```sql
SELECT id_kelas, nama_kelas, jurusan_id FROM kelas LIMIT 10;
```

**Harusnya:** `jurusan_id` berisi nilai (tidak NULL), contoh:
| id_kelas | nama_kelas | jurusan_id |
|----------|-----------|-----------|
| 1 | 10 A | 1 |
| 2 | 10 B | 1 |
| 3 | 11 A | 2 |

## 3. Jika `jurusan_id` NULL atau tidak ada

### Option A: Tambah Kolom (jika tidak ada)
```sql
ALTER TABLE kelas ADD COLUMN jurusan_id INT;
ALTER TABLE kelas ADD FOREIGN KEY (jurusan_id) REFERENCES jurusan(id_jurusan);
```

### Option B: Update Existing Data (jika sudah ada kolom)
```sql
-- Misal: kelas 10A-10C dari IPA, kelas 11A-11C dari IPS
UPDATE kelas SET jurusan_id = 1 WHERE nama_kelas IN ('10 A', '10 B', '10 C');
UPDATE kelas SET jurusan_id = 2 WHERE nama_kelas IN ('11 A', '11 B', '11 C');
```

## 4. Test Database Query

Jalankan test file di browser:
```
http://localhost/sis/proses/test_kelas_endpoint.php?jurusan_id=1
```

Harusnya return JSON dengan kelas yang ada di jurusan tersebut.

## 5. Jika masih tidak muncul

Buka Developer Console (F12) di browser:
1. Buka halaman Absensi Kelas
2. Buka Modal
3. Pilih Jurusan
4. Lihat di Console tab apakah ada error message
5. Cek Network tab - request ke `get_kelas_by_jurusan.php` harusnya return 200 dan data JSON

---

**Paling Mungkin Penyebab:**
✗ `jurusan_id` di `kelas` table masih NULL atau belum diisi
✓ Query benar, endpoint benar
✓ AJAX function benar

**Solusi Cepat:**
Pastikan setiap kelas sudah memiliki `jurusan_id` yang tepat di database.
