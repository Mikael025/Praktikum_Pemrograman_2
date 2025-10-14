<!-- a3d74434-58fe-42c6-bd8c-b772ea9aeaf0 b56be45c-1910-4e92-a7dc-b3c3e4b289b3 -->
# Dashboard Dosen - Kerangka dan Desain Lengkap

## 1. Membuat Layout Dosen

**File**: `aksara/resources/views/components/layouts/dosen.blade.php`

Membuat layout khusus dosen yang mirip dengan layout admin, dengan:

- Sidebar kiri: Logo Aksara, menu Dashboard, Penelitian, Pengabdian Masyarakat, Informasi/Berita, Logout
- Topbar: Logo + nama dosen di sisi kanan
- Menggunakan komponen `sidebar-link` yang sudah ada

## 2. Update Dashboard Dosen - Halaman Utama

**File**: `aksara/resources/views/dashboard-dosen.blade.php`

Mengganti konten dengan:

- Welcome card dengan sapaan personal "Selamat Datang, {Nama Dosen}"
- Filter & Search Bar (tahun dan pencarian)
- Statistik Penelitian (4 cards):
- Usulan Dokumen Penelitian
- Dokumen Belum Lengkap (khusus dosen)
- Seleksi Dokumen Penelitian
- Dokumen Lolos Penelitian
- Statistik Pengabdian (4 cards):
- Usulan Dokumen Pengabdian
- Dokumen Belum Lengkap (khusus dosen)
- Seleksi Dokumen Pengabdian
- Dokumen Lolos Pengabdian

Menggunakan komponen `stat-card` dan `filter-bar` yang sudah ada.

## 3. Database - Migration untuk Penelitian

**File**: `aksara/database/migrations/YYYY_MM_DD_HHMMSS_create_penelitian_table.php`

Membuat tabel `penelitian` dengan kolom:

- id, user_id (foreign key ke users)
- judul, tahun, tim_peneliti (text/json)
- sumber_dana, status (enum: draft, menunggu_verifikasi, terverifikasi, ditolak, berjalan, selesai)
- timestamps

## 4. Database - Migration untuk Pengabdian

**File**: `aksara/database/migrations/YYYY_MM_DD_HHMMSS_create_pengabdian_table.php`

Membuat tabel `pengabdian` dengan kolom:

- id, user_id (foreign key ke users)
- judul, tahun, tim_pelaksana (text/json)
- lokasi, mitra, status (enum: draft, menunggu_verifikasi, terverifikasi, ditolak, berjalan, selesai)
- timestamps

## 5. Database - Migration untuk Dokumen Penelitian

**File**: `aksara/database/migrations/YYYY_MM_DD_HHMMSS_create_penelitian_documents_table.php`

Membuat tabel `penelitian_documents` dengan kolom:

- id, penelitian_id (foreign key)
- jenis_dokumen (proposal, laporan_akhir, sertifikat, dll)
- nama_file, path_file, uploaded_at
- timestamps

## 6. Database - Migration untuk Dokumen Pengabdian

**File**: `aksara/database/migrations/YYYY_MM_DD_HHMMSS_create_pengabdian_documents_table.php`

Membuat tabel `pengabdian_documents` dengan kolom:

- id, pengabdian_id (foreign key)
- jenis_dokumen (proposal, laporan_akhir, sertifikat, dll)
- nama_file, path_file, uploaded_at
- timestamps

## 7. Model Penelitian

**File**: `aksara/app/Models/Penelitian.php`

Membuat model dengan:

- Fillable attributes
- Relationship ke User (belongsTo)
- Relationship ke PenelitianDocument (hasMany)
- Cast untuk tim_peneliti (array/json)

## 8. Model Pengabdian

**File**: `aksara/app/Models/Pengabdian.php`

Membuat model dengan:

- Fillable attributes
- Relationship ke User (belongsTo)
- Relationship ke PengabdianDocument (hasMany)
- Cast untuk tim_pelaksana (array/json)

## 9. Model PenelitianDocument

**File**: `aksara/app/Models/PenelitianDocument.php`

Membuat model dengan:

- Fillable attributes
- Relationship ke Penelitian (belongsTo)

## 10. Model PengabdianDocument

**File**: `aksara/app/Models/PengabdianDocument.php`

Membuat model dengan:

- Fillable attributes
- Relationship ke Pengabdian (belongsTo)

## 11. Controller - DosenDashboardController

**File**: `aksara/app/Http/Controllers/DosenDashboardController.php`

Membuat controller untuk dashboard dosen dengan method:

- `index()`: Menampilkan statistik penelitian & pengabdian dosen yang login

## 12. Controller - DosenPenelitianController

**File**: `aksara/app/Http/Controllers/DosenPenelitianController.php`

Membuat resource controller dengan method:

- `index()`: List penelitian dosen
- `create()`: Form tambah penelitian
- `store()`: Simpan penelitian baru
- `show()`: Detail penelitian
- `edit()`: Form edit penelitian
- `update()`: Update penelitian
- `destroy()`: Hapus penelitian
- `uploadDocument()`: Upload dokumen pendukung

## 13. Controller - DosenPengabdianController

**File**: `aksara/app/Http/Controllers/DosenPengabdianController.php`

Membuat resource controller dengan method:

- `index()`: List pengabdian dosen
- `create()`: Form tambah pengabdian
- `store()`: Simpan pengabdian baru
- `show()`: Detail pengabdian
- `edit()`: Form edit pengabdian
- `update()`: Update pengabdian
- `destroy()`: Hapus pengabdian
- `uploadDocument()`: Upload dokumen pendukung

## 14. View - Halaman Penelitian Dosen

**File**: `aksara/resources/views/dosen/penelitian/index.blade.php`

Membuat halaman dengan:

- Tombol "Tambah Penelitian Baru"
- Filter (Tahun, Status) & Search Bar
- Tabel CRUD dengan kolom: Judul, Tahun, Tim Peneliti, Sumber Dana, Status
- Aksi: Lihat Detail, Edit, Upload Dokumen, Hapus

## 15. View - Halaman Pengabdian Dosen

**File**: `aksara/resources/views/dosen/pengabdian/index.blade.php`

Membuat halaman dengan:

- Tombol "Tambah Pengabdian Baru"
- Filter (Tahun, Status) & Search Bar
- Tabel CRUD dengan kolom: Judul, Tahun, Tim Pelaksana, Lokasi, Mitra, Status
- Aksi: Lihat Detail, Edit, Upload Dokumen, Hapus

## 16. View - Placeholder Informasi/Berita

**File**: `aksara/resources/views/dosen/informasi.blade.php`

Membuat halaman placeholder sederhana dengan pesan "Halaman dalam pengembangan".

## 17. Update Routes untuk Dosen

**File**: `aksara/routes/web.php`

Menambahkan routes untuk:

- Dashboard dosen (update existing)
- Resource routes untuk penelitian dosen
- Resource routes untuk pengabdian dosen
- Route untuk upload dokumen
- Route untuk informasi/berita (placeholder)

Semua routes menggunakan middleware: `['auth', 'verified', 'role:dosen']`

## 18. Update Model User

**File**: `aksara/app/Models/User.php`

Menambahkan relationship:

- `penelitian()` hasMany
- `pengabdian()` hasMany

## Catatan Implementasi

- Menggunakan Tailwind CSS untuk styling (konsisten dengan admin)
- Komponen yang digunakan: `stat-card`, `filter-bar`, `sidebar-link`
- Status menggunakan placeholder yang mudah diubah (enum di migration)
- Data awal menggunakan placeholder "—" seperti di halaman admin
- Upload dokumen menggunakan storage Laravel (public disk)
- Validasi form akan ditambahkan di controller

### To-dos

- [ ] Membuat layout dosen dengan sidebar dan topbar
- [ ] Update halaman dashboard dosen dengan statistik cards
- [ ] Membuat migrations untuk penelitian, pengabdian, dan dokumen
- [ ] Membuat models Penelitian, Pengabdian, dan Documents
- [ ] Membuat controllers untuk dashboard, penelitian, dan pengabdian dosen
- [ ] Membuat halaman index penelitian dosen dengan tabel CRUD
- [ ] Membuat halaman index pengabdian dosen dengan tabel CRUD
- [ ] Membuat halaman placeholder untuk Informasi/Berita
- [ ] Update routes untuk semua halaman dosen
- [ ] Update model User dengan relationship penelitian dan pengabdian