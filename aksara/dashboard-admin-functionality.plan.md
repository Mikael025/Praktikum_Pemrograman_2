<!-- 7ea54f96-04f6-4252-b5a8-2af13cc974b4 e6059579-7545-4096-b5b7-7f86b9c38208 -->
# Dashboard Admin - Implementasi Fungsionalitas CRUD

## Overview

Melengkapi fungsionalitas CRUD untuk dashboard admin (penelitian dan pengabdian) agar setara dengan dashboard dosen. Admin dapat melihat semua data dari semua dosen dan melakukan verifikasi.

## 1. Controller - AdminDashboardController

**File**: `aksara/app/Http/Controllers/AdminDashboardController.php`

Membuat controller untuk dashboard admin dengan method:

- `index()`: Menampilkan statistik penelitian & pengabdian SEMUA dosen (bukan hanya satu user)
- Hitung: Total Penelitian Aktif, Menunggu Verifikasi, Selesai Diverifikasi, Ditolak
- Hitung: Total Pengabdian Aktif, Menunggu Verifikasi, Selesai Diverifikasi, Ditolak
- Support filter berdasarkan tahun

## 2. Controller - AdminPenelitianController

**File**: `aksara/app/Http/Controllers/AdminPenelitianController.php`

Membuat resource controller untuk admin kelola penelitian:

- `index()`: List SEMUA penelitian dari semua dosen dengan filter (tahun, status) & search
- `show()`: Detail penelitian lengkap dengan dokumen dan info dosen
- `edit()`: Form edit penelitian (admin bisa edit data dosen)
- `update()`: Update penelitian
- `destroy()`: Hapus penelitian
- `verify()`: Method khusus untuk verifikasi penelitian (ubah status)
- `reject()`: Method khusus untuk menolak penelitian

## 3. Controller - AdminPengabdianController

**File**: `aksara/app/Http/Controllers/AdminPengabdianController.php`

Membuat resource controller untuk admin kelola pengabdian:

- `index()`: List SEMUA pengabdian dari semua dosen dengan filter & search
- `show()`: Detail pengabdian lengkap dengan dokumen dan info dosen
- `edit()`: Form edit pengabdian
- `update()`: Update pengabdian
- `destroy()`: Hapus pengabdian
- `verify()`: Method khusus untuk verifikasi pengabdian
- `reject()`: Method khusus untuk menolak pengabdian

## 4. View - Detail Penelitian Admin

**File**: `aksara/resources/views/admin/penelitian/show.blade.php`

Halaman detail penelitian untuk admin:

- Info dosen pemilik (nama, email, NIDN)
- Detail penelitian lengkap (judul, tahun, tim, sumber dana, status)
- List dokumen yang sudah diupload
- Tombol aksi: Edit, Verifikasi, Tolak, Hapus
- Riwayat perubahan status (opsional)

## 5. View - Edit Penelitian Admin

**File**: `aksara/resources/views/admin/penelitian/edit.blade.php`

Form edit penelitian untuk admin:

- Semua field penelitian dapat diedit
- Dropdown untuk ubah status
- Textarea untuk catatan/keterangan
- Tombol Simpan & Batal

## 6. View - Detail Pengabdian Admin

**File**: `aksara/resources/views/admin/pengabdian/show.blade.php`

Halaman detail pengabdian untuk admin (mirip dengan penelitian)

## 7. View - Edit Pengabdian Admin

**File**: `aksara/resources/views/admin/pengabdian/edit.blade.php`

Form edit pengabdian untuk admin (mirip dengan penelitian)

## 8. Update Routes Admin

**File**: `aksara/routes/web.php`

Update routes admin yang sudah ada dari closure menjadi controller:

- Dashboard admin → AdminDashboardController@index
- Resource routes untuk penelitian admin (tanpa create & store, hanya show, edit, update, destroy)
- Resource routes untuk pengabdian admin (tanpa create & store)
- Route khusus untuk verifikasi & reject

## 9. Update View - Dashboard Admin

**File**: `aksara/resources/views/dashboard-admin.blade.php`

Mengganti nilai placeholder (0) dengan data real dari controller:

- Passing statistik dari AdminDashboardController
- Implementasi filter tahun yang fungsional

## 10. Update View - Index Penelitian Admin

**File**: `aksara/resources/views/penelitian/index.blade.php`

Melengkapi dengan data real:

- Loop data penelitian dari database
- Implementasi filter & search yang fungsional
- Link aksi yang benar (show, edit, delete, verify)
- Tampilkan nama dosen di setiap row (tambah kolom)
- Badge status dengan warna berbeda (draft, menunggu_verifikasi, terverifikasi, dll)

## 11. Update View - Index Pengabdian Admin

**File**: `aksara/resources/views/pengabdian/index.blade.php`

Melengkapi dengan data real (mirip penelitian):

- Loop data pengabdian dari database
- Implementasi filter & search
- Link aksi yang benar
- Tampilkan nama dosen
- Badge status dengan warna

## 12. Component - Status Badge

**File**: `aksara/resources/views/components/status-badge.blade.php`

Membuat component untuk badge status dengan warna otomatis:

- draft → gray
- menunggu_verifikasi → yellow
- terverifikasi → green
- ditolak → red
- berjalan → blue
- selesai → emerald

## 13. Request Validation - AdminPenelitianRequest

**File**: `aksara/app/Http/Requests/AdminPenelitianRequest.php`

Form request untuk validasi update penelitian oleh admin:

- Validasi semua field penelitian
- Rules untuk status (hanya status valid)

## 14. Request Validation - AdminPengabdianRequest

**File**: `aksara/app/Http/Requests/AdminPengabdianRequest.php`

Form request untuk validasi update pengabdian oleh admin

## Fitur Tambahan

- Notifikasi sukses/error menggunakan session flash
- Konfirmasi sebelum hapus (JavaScript)
- Modal untuk verifikasi & reject dengan catatan
- Pagination untuk list data (15 items per page)

## Perbedaan Admin vs Dosen

**Dashboard Dosen:**

- Hanya lihat data milik sendiri
- Bisa create, edit, delete data sendiri
- Bisa upload dokumen
- Tidak bisa verifikasi

**Dashboard Admin:**

- Lihat semua data dari semua dosen
- Bisa edit & delete data semua dosen
- TIDAK bisa create (data dibuat oleh dosen)
- TIDAK bisa upload dokumen (dokumen diupload dosen)
- Bisa verifikasi & reject
- Lihat info dosen pemilik data

### To-dos

- [x] Membuat AdminDashboardController dengan statistik untuk semua dosen
- [x] Membuat AdminPenelitianController dengan fungsi CRUD dan verifikasi
- [x] Membuat AdminPengabdianController dengan fungsi CRUD dan verifikasi
- [x] Membuat component status-badge untuk tampilan status dengan warna
- [x] Membuat halaman detail penelitian untuk admin (show.blade.php)
- [x] Membuat halaman edit penelitian untuk admin (edit.blade.php)
- [x] Membuat halaman detail pengabdian untuk admin (show.blade.php)
- [x] Membuat halaman edit pengabdian untuk admin (edit.blade.php)
- [x] Update dashboard-admin.blade.php dengan data real dari controller
- [x] Update penelitian/index.blade.php dengan data real, filter, dan aksi
- [x] Update pengabdian/index.blade.php dengan data real, filter, dan aksi
- [x] Membuat form request validation untuk admin (penelitian & pengabdian)
- [x] Update routes admin dari closure ke controller dengan route verifikasi