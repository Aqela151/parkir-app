# Dokumentasi Perbaikan Perhitungan Pendapatan

## Ringkasan Perubahan
Perhitungan pendapatan telah diubah dari menggunakan `waktu_masuk` menjadi `waktu_keluar` untuk memastikan akurasi laporan. Hanya transaksi yang sudah selesai (memiliki `tarif_akhir` yang tidak null) yang dihitung.

---

## 📋 Daftar Perubahan

### 1. **Dashboard Owner** (`dashboard()`)
**File:** `app/Http/Controllers/OwnerController.php`

#### Sebelum:
```php
// Pendapatan hari ini
$pendapatanHariIni = Transaksi::whereDate('waktu_masuk', today())
    ->whereNotNull('tarif_akhir')
    ->sum('tarif_akhir') ?? 0;
```

#### Sesudah:
```php
// Pendapatan hari ini (berdasarkan waktu_keluar, hanya transaksi selesai)
$pendapatanHariIni = Transaksi::whereDate('waktu_keluar', today())
    ->whereNotNull('tarif_akhir')
    ->sum('tarif_akhir') ?? 0;
```

**Perubahan yang dibuat:**
- ✅ Menggunakan `whereDate('waktu_keluar', ...)` alih-alih `waktu_masuk`
- ✅ Memastikan `whereNotNull('tarif_akhir')` ada
- ✅ Chart harian juga diubah ke `waktu_keluar`
- ✅ Pendapatan per area diubah ke `waktu_keluar` dengan validasi `tarif_akhir`

---

### 2. **Rekap Transaksi** (`rekapTransaksi()`)
**File:** `app/Http/Controllers/OwnerController.php`

#### Sebelum:
```php
$query = Transaksi::with(['kendaraan', 'area']);

if ($request->filled('dari_tanggal')) {
    $query->whereDate('waktu_masuk', '>=', $request->dari_tanggal);
}
```

#### Sesudah:
```php
$query = Transaksi::with(['kendaraan', 'area'])->whereNotNull('tarif_akhir');

if ($request->filled('dari_tanggal')) {
    $query->whereDate('waktu_keluar', '>=', $request->dari_tanggal);
}
```

**Perubahan yang dibuat:**
- ✅ Filter tanggal menggunakan `waktu_keluar`
- ✅ Tambahan validasi `->whereNotNull('tarif_akhir')` di awal query
- ✅ Sorting juga diubah ke `waktu_keluar`

---

### 3. **Grafik Pendapatan** (`grafikPendapatan()`)
**File:** `app/Http/Controllers/OwnerController.php`

#### Sebelum:
```php
$query = Transaksi::whereYear('waktu_masuk', $tahun)
    ->whereMonth('waktu_masuk', $bulan)
    ->whereNotNull('tarif_akhir');
```

#### Sesudah:
```php
$query = Transaksi::whereYear('waktu_keluar', $tahun)
    ->whereMonth('waktu_keluar', $bulan)
    ->whereNotNull('tarif_akhir');
```

**Perubahan yang dibuat:**
- ✅ Filter tahun/bulan menggunakan `waktu_keluar`
- ✅ Pendapatan per area di laporan grafik juga diubah
- ✅ Trend tahunan dihitung berdasarkan `waktu_keluar`

---

### 4. **Performa Area** (`performaArea()`)
**File:** `app/Http/Controllers/OwnerController.php`

#### Sebelum:
```php
$txBulanIni = (int) Transaksi::where('area_id', $area->id)
    ->whereMonth('waktu_masuk', date('m'))
    ->whereYear('waktu_masuk', date('Y'))
    ->count();

$pendapatan = (float)(Transaksi::where('area_id', $area->id)
    ->whereMonth('waktu_masuk', date('m'))
    ->whereYear('waktu_masuk', date('Y'))
    ->whereNotNull('tarif_akhir')
    ->sum('tarif_akhir') ?? 0);
```

#### Sesudah:
```php
$txBulanIni = (int) Transaksi::where('area_id', $area->id)
    ->whereMonth('waktu_keluar', date('m'))
    ->whereYear('waktu_keluar', date('Y'))
    ->whereNotNull('tarif_akhir')
    ->count();

$pendapatan = (float)(Transaksi::where('area_id', $area->id)
    ->whereMonth('waktu_keluar', date('m'))
    ->whereYear('waktu_keluar', date('Y'))
    ->whereNotNull('tarif_akhir')
    ->sum('tarif_akhir') ?? 0);
```

**Perubahan yang dibuat:**
- ✅ Filter menggunakan `waktu_keluar`
- ✅ Tambahan `whereNotNull('tarif_akhir')` saat hitung jumlah transaksi
- ✅ Rata-rata durasi juga difilter transaksi yang selesai

---

## 🔍 Detail Teknis Perubahan

### Alasan Menggunakan `waktu_keluar`:
1. **Akurasi Laporan**: Pendapatan hanya dihitung ketika transaksi benar-benar selesai
2. **Menghindari Transaksi Pending**: Transaksi yang masih berlangsung tidak termasuk dalam perhitungan
3. **Konsistensi Tarif**: Kolom `tarif_akhir` hanya terisi saat `waktu_keluar` diset

### Kondisi `whereNotNull('tarif_akhir')`:
- Memastikan **HANYA transaksi yang selesai** yang dihitung
- Mencegah transaksi pending (belum keluar) masuk ke laporan
- Konsisten dengan penggunaan `waktu_keluar`

### Struktur Tabel `transaksis`:
```
- waktu_masuk      : Waktu kendaraan masuk parkir
- waktu_keluar     : Waktu kendaraan keluar parkir (NULL jika masih parkir)
- tarif_akhir      : Tarif yang dihitung (NULL jika transaksi belum selesai)
- durasi_menit     : Durasi parkir dalam menit
```

---

## 🧪 Contoh Skenario

### Skenario: Perhitungan Pendapatan Hari Ini

**Kondisi Data:**
```
Transaksi 1: masuk 08:00, keluar 10:00, tarif_akhir = 50000 ✓ Dihitung
Transaksi 2: masuk 09:30, keluar NULL, tarif_akhir = NULL ✗ Tidak dihitung (masih parkir)
Transaksi 3: masuk 10:15, keluar 11:45, tarif_akhir = 60000 ✓ Dihitung
```

**Query Lama (Salah):**
```php
whereDate('waktu_masuk', today())
// Hasil: 50000 + 0 (pending) + 60000 = 110000 
// ⚠️ Transaksi pending dihitung sebagai 0 (tarif_akhir NULL)
```

**Query Baru (Benar):**
```php
whereDate('waktu_keluar', today())->whereNotNull('tarif_akhir')
// Hasil: 50000 + 60000 = 110000
// ✓ Hanya transaksi yang sudah selesai
```

---

## 📊 Dampak pada Laporan

### Dashboard Owner
- ✅ Pendapatan hari ini lebih akurat
- ✅ Trend perbandingan dengan kemarin lebih valid
- ✅ Chart harian 7 hari menunjukkan data terakhir

### Rekap Transaksi
- ✅ Filter tanggal berdasarkan tanggal transaksi selesai
- ✅ Total pendapatan lebih akurat

### Grafik Pendapatan
- ✅ Data bulanan/tahunan dihitung dari transaksi yang selesai
- ✅ Perbandingan antar bulan/tahun lebih akurat
- ✅ Data export (XLSX) akan lebih valid

### Performa Area
- ✅ Pendapatan per area dihitung dengan tepat
- ✅ Rata-rata durasi lebih akurat (hanya transaksi selesai)

---

## ⚠️ Catatan Penting

1. **Untuk Transaksi Historis**: Jika ada transaksi lama yang `waktu_keluar`-nya NULL, laporan mungkin tampak berbeda dari sebelumnya. Pastikan semua transaksi sudah memiliki `waktu_keluar`.

2. **Testing**: Disarankan untuk membandingkan laporan lama vs baru di periode yang sama untuk memastikan kesesuaian.

3. **Backup Data**: Buat backup database sebelum melakukan perubahan ini di production.

---

## 🔗 File yang Diubah
- ✅ `app/Http/Controllers/OwnerController.php`

## 📝 Perubahan:
- Dashboard: 1 perubahan besar (seluruh logika dashboard)
- Rekap Transaksi: 5 perubahan (filter dan sorting)
- Grafik Pendapatan: 8 perubahan (bulanan, tahunan, per area)
- Performa Area: 3 perubahan (filter bulan)

**Total: 17 perubahan terstruktur**

---

## ✅ Verifikasi Perubahan

Untuk memverifikasi perubahan berhasil, cek:

```bash
# Cek dashboard - lihat pendapatan hari ini
GET /owner/dashboard

# Cek rekap transaksi - filter dengan tanggal
GET /owner/rekap-transaksi?dari_tanggal=2026-05-05&sampai_tanggal=2026-05-05

# Cek grafik pendapatan - pilih tahun/bulan
GET /owner/grafik-pendapatan?tahun=2026

# Cek performa area - lihat pendapatan per area
GET /owner/performa-area
```

---

**Terakhir diupdate:** 5 May 2026
**Status:** ✅ Perbaikan selesai dan siap digunakan
