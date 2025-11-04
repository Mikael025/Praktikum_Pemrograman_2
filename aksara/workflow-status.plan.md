<!-- 51b90644-ca1f-4f40-9e49-a2a743b3d159 b7982f21-3782-4383-bc47-2d9ab96cf14f -->
# Perubahan Workflow Status Penelitian & Pengabdian

## 1. Database Migration - Update Status Enum

**File**: `aksara/database/migrations/2025_10_20_update_status_workflow.php`

Membuat migration baru untuk mengubah enum status dari 6 status lama menjadi 6 status baru:

- Status lama: `draft`, `menunggu_verifikasi`, `terverifikasi`, `ditolak`, `berjalan`, `selesai`
- Status baru: `diusulkan`, `tidak_lolos`, `lolos_perlu_revisi`, `lolos`, `revisi_pra_final`, `selesai`

Migration akan:

- Mengubah kolom status di tabel `penelitian` dan `pengabdian`
- Memetakan data lama ke status baru (draft → diusulkan, terverifikasi → lolos, etc)

## 2. Model Updates

**Files**: `aksara/app/Models/Penelitian.php`, `aksara/app/Models/Pengabdian.php`

Tambahkan helper methods untuk workflow validation:

- `canBeEditedByDosen()` - cek apakah dosen boleh edit (status: diusulkan, lolos_perlu_revisi, revisi_pra_final)
- `canBeDeleted()` - cek apakah boleh dihapus (hanya status: diusulkan)
- `requiresProposal()` - cek apakah wajib upload proposal
- `requiresFinalDocuments()` - cek apakah wajib upload laporan akhir & sertifikat
- `getRequiredDocuments()` - return array jenis dokumen yang wajib per status

## 3. Exception Handling - Custom Exceptions

**File**: `aksara/app/Exceptions/WorkflowException.php`

Buat custom exception untuk handle workflow errors:

- `InvalidStatusTransitionException` - transisi status tidak valid
- `MissingRequiredDocumentsException` - dokumen wajib belum diupload
- `UnauthorizedActionException` - action tidak diizinkan sesuai status
- `DocumentUploadException` - error saat upload dokumen

## 4. Form Requests - Validation Update

**Files**:

- `aksara/app/Http/Requests/StorePenelitianRequest.php` (baru)
- `aksara/app/Http/Requests/UpdatePenelitianRequest.php` (baru)
- `aksara/app/Http/Requests/StorePengabdianRequest.php` (baru)
- `aksara/app/Http/Requests/UpdatePengabdianRequest.php` (baru)
- Update: `aksara/app/Http/Requests/AdminPenelitianRequest.php`
- Update: `aksara/app/Http/Requests/AdminPengabdianRequest.php`

Validasi mencakup:

- File upload proposal wajib saat create
- File upload dokumen opsional/wajib per status
- Validasi status hanya yang valid
- Catatan verifikasi wajib saat tolak/revisi

## 5. Controller - Dosen (Penelitian & Pengabdian)

**Files**:

- `aksara/app/Http/Controllers/DosenPenelitianController.php`
- `aksara/app/Http/Controllers/DosenPengabdianController.php`

Perubahan:

- `store()`: Upload proposal wajib, set status `diusulkan`
- `update()`: Upload proposal + dokumen lain, cek permission via model method
- `destroy()`: Cek permission via model method
- Hapus method `uploadDocument()` (sudah merged di create/edit)
- Tambah exception handling untuk semua action

## 6. Controller - Admin (Penelitian & Pengabdian)

**Files**:

- `aksara/app/Http/Controllers/AdminPenelitianController.php`
- `aksara/app/Http/Controllers/AdminPengabdianController.php`

Perubahan method `verify()` dan `reject()` menjadi action terpisah per status:

- `setTidakLolos(Request $request, Penelitian $penelitian)` - dari diusulkan → tidak_lolos
- `setLolosPerluRevisi(Request $request, Penelitian $penelitian)` - dari diusulkan → lolos_perlu_revisi
- `setLolos(Request $request, Penelitian $penelitian)` - dari diusulkan/lolos_perlu_revisi → lolos
- `setRevisiPraFinal(Request $request, Penelitian $penelitian)` - dari lolos → revisi_pra_final
- `setSelesai(Request $request, Penelitian $penelitian)` - dari lolos/revisi_pra_final → selesai

Setiap method include:

- Validasi status transition
- Validasi catatan wajib
- Exception handling
- Flash message

## 7. View Components - Status Badge Update

**File**: `aksara/resources/views/components/status-badge.blade.php`

Update mapping status baru dengan warna:

- `diusulkan` → blue
- `tidak_lolos` → red
- `lolos_perlu_revisi` → yellow
- `lolos` → green
- `revisi_pra_final` → orange
- `selesai` → emerald

## 8. Dosen Views - Form Create dengan Upload

**Files**:

- `aksara/resources/views/dosen/penelitian/create.blade.php`
- `aksara/resources/views/dosen/pengabdian/create.blade.php`

Tambahkan:

- Section upload proposal (wajib)
- Section upload dokumen pendukung (opsional)
- File validation feedback
- Exception error display

## 9. Dosen Views - Form Edit dengan Upload

**Files**:

- `aksara/resources/views/dosen/penelitian/edit.blade.php`
- `aksara/resources/views/dosen/pengabdian/edit.blade.php`

Tambahkan:

- Section upload proposal (wajib jika belum ada)
- Section upload dokumen lain (conditional per status)
- Tampilkan dokumen yang sudah diupload
- Option hapus/ganti dokumen
- Conditional enable/disable edit berdasarkan status
- Exception error display

## 10. Dosen Views - Index dengan Aksi Conditional

**Files**:

- `aksara/resources/views/dosen/penelitian/index.blade.php`
- `aksara/resources/views/dosen/pengabdian/index.blade.php`

Perubahan:

- Hapus tombol "Upload" terpisah
- Tombol "Edit" conditional (hanya jika `canBeEditedByDosen()`)
- Tombol "Hapus" conditional (hanya jika `canBeDeleted()`)
- Tampilkan catatan verifikasi jika ada
- Exception error display

## 11. Admin Views - Index dengan Aksi per Status

**Files**:

- `aksara/resources/views/penelitian/index.blade.php` (admin)
- `aksara/resources/views/pengabdian/index.blade.php` (admin)

Perubahan kolom aksi:

- Lihat (selalu ada)
- Edit (selalu ada)
- Verifikasi (conditional per status):
- Status `diusulkan`: tampilkan tombol "Tidak Lolos", "Lolos Perlu Revisi", "Lolos"
- Status `lolos_perlu_revisi`: tampilkan tombol "Lolos"
- Status `lolos`: tampilkan tombol "Revisi Pra-final", "Selesai"
- Status `revisi_pra_final`: tampilkan tombol "Selesai"
- Exception error display

## 12. Admin Views - Detail dengan Form Verifikasi

**Files**:

- `aksara/resources/views/admin/penelitian/show.blade.php`
- `aksara/resources/views/admin/pengabdian/show.blade.php`

Ganti form verifikasi tunggal dengan multiple forms conditional:

- Jika status `diusulkan`: form 3 tombol (Tidak Lolos, Lolos Perlu Revisi, Lolos)
- Jika status `lolos_perlu_revisi`: form 1 tombol (Lolos)
- Jika status `lolos`: form 2 tombol (Revisi Pra-final, Selesai)
- Jika status `revisi_pra_final`: form 1 tombol (Selesai)
- Setiap form punya textarea catatan (wajib untuk tolak/revisi, opsional untuk approve)
- Exception error display

## 13. Dashboard Updates - Statistik Baru

**Files**:

- `aksara/app/Http/Controllers/AdminDashboardController.php`
- `aksara/app/Http/Controllers/DosenDashboardController.php`
- `aksara/resources/views/dashboard-admin.blade.php`
- `aksara/resources/views/dashboard-dosen.blade.php`

### Struktur Kartu Statistik (4 Kartu untuk Penelitian + 4 Kartu untuk Pengabdian)

**Untuk Administrator (Data Keseluruhan dari Semua Dosen):**

Penelitian:

1. Jumlah Penelitian Diusulkan → status: `diusulkan`
2. Jumlah Penelitian Tidak Lolos → status: `tidak_lolos`
3. Jumlah Penelitian Lolos → status: `lolos_perlu_revisi`, `lolos`, `revisi_pra_final`
4. Jumlah Penelitian Selesai → status: `selesai`

Pengabdian:

1. Jumlah Pengabdian Diusulkan → status: `diusulkan`
2. Jumlah Pengabdian Tidak Lolos → status: `tidak_lolos`
3. Jumlah Pengabdian Lolos → status: `lolos_perlu_revisi`, `lolos`, `revisi_pra_final`
4. Jumlah Pengabdian Selesai → status: `selesai`

**Untuk Dosen (Data Personal/Pribadi Dosen yang Login):**

Penelitian Saya:

1. Jumlah Penelitian Diusulkan → status: `diusulkan` (milik dosen login)
2. Jumlah Penelitian Tidak Lolos → status: `tidak_lolos` (milik dosen login)
3. Jumlah Penelitian Lolos → status: `lolos_perlu_revisi`, `lolos`, `revisi_pra_final` (milik dosen login)
4. Jumlah Penelitian Selesai → status: `selesai` (milik dosen login)

Pengabdian Saya:

1. Jumlah Pengabdian Diusulkan → status: `diusulkan` (milik dosen login)
2. Jumlah Pengabdian Tidak Lolos → status: `tidak_lolos` (milik dosen login)
3. Jumlah Pengabdian Lolos → status: `lolos_perlu_revisi`, `lolos`, `revisi_pra_final` (milik dosen login)
4. Jumlah Pengabdian Selesai → status: `selesai` (milik dosen login)

## 14. Routes Update

**File**: `aksara/routes/web.php`

Tambahkan routes baru untuk action admin:

- `POST /admin/penelitian/{penelitian}/tidak-lolos` → `AdminPenelitianController@setTidakLolos`
- `POST /admin/penelitian/{penelitian}/lolos-perlu-revisi` → `AdminPenelitianController@setLolosPerluRevisi`
- `POST /admin/penelitian/{penelitian}/lolos` → `AdminPenelitianController@setLolos`
- `POST /admin/penelitian/{penelitian}/revisi-pra-final` → `AdminPenelitianController@setRevisiPraFinal`
- `POST /admin/penelitian/{penelitian}/selesai` → `AdminPenelitianController@setSelesai`

Sama untuk pengabdian. Hapus route lama `verify` dan `reject`.

## 15. Error Handling & User Feedback

Di semua controller, tambahkan try-catch untuk:

- WorkflowException → redirect back dengan error message
- ValidationException → redirect back dengan validation errors
- FileUploadException → redirect back dengan error message
- General Exception → log error, redirect dengan generic message

Di semua view, tambahkan:

- Alert success message
- Alert error message
- Inline validation errors
- Loading state untuk form submit

### To-dos

- [ ] Buat migration untuk update status enum penelitian dan pengabdian
- [ ] Tambahkan helper methods di Model Penelitian dan Pengabdian untuk workflow validation
- [ ] Buat custom exception classes untuk workflow errors
- [ ] Update dan buat form request classes untuk validation
- [ ] Update DosenPenelitianController dan DosenPengabdianController dengan upload terintegrasi dan exception handling
- [ ] Update AdminPenelitianController dan AdminPengabdianController dengan action terpisah per status
- [ ] Update component status-badge dengan mapping status baru
- [ ] Update form create dosen dengan upload proposal wajib
- [ ] Update form edit dosen dengan upload dokumen dan conditional enable/disable
- [ ] Update halaman index dosen dengan aksi conditional
- [ ] Update halaman index admin dengan aksi verifikasi per status
- [ ] Update halaman detail admin dengan multiple form verifikasi conditional
- [ ] Update controller dan view dashboard untuk statistik status baru
- [ ] Update routes untuk action admin yang baru
- [ ] Testing workflow lengkap dari diusulkan sampai selesai