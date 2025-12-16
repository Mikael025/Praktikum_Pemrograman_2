# 📱 DOKUMENTASI USER INTERFACE & USER EXPERIENCE
## Sistem AKSARA (Aplikasi Kegiatan Sains, Riset, dan Abdi Masyarakat)

---

## 📋 Daftar Isi

1. [Executive Summary](#executive-summary)
2. [Design System](#design-system)
3. [Layout & Navigation](#layout--navigation)
4. [Components Library](#components-library)
5. [Key Screens](#key-screens)
6. [User Flows](#user-flows)
7. [Interactive Elements](#interactive-elements)
8. [Responsive Design](#responsive-design)
9. [Accessibility & Usability](#accessibility--usability)
10. [Best Practices Implementation](#best-practices-implementation)

---

## 1. Executive Summary

### 1.1 Tujuan Desain
Sistem AKSARA dirancang dengan fokus pada **kemudahan penggunaan**, **konsistensi visual**, dan **aksesibilitas** untuk tiga jenis pengguna utama: Administrator LPPM, Dosen, dan Pengunjung Web.

### 1.2 Prinsip Desain Utama

#### ✅ **Consistency (Konsistensi)**
- Penggunaan design system yang konsisten di seluruh halaman
- Pattern interaksi yang seragam untuk aksi yang sama
- Terminologi dan bahasa yang konsisten

#### ✅ **Clarity (Kejelasan)**
- Hierarki visual yang jelas menggunakan typography dan spacing
- Navigasi yang intuitif dengan label deskriptif
- Feedback visual yang immediate untuk setiap aksi user

#### ✅ **Efficiency (Efisiensi)**
- Quick action buttons untuk task yang sering dilakukan
- Form yang terstruktur dengan validasi real-time
- Shortcut akses ke fitur penting

#### ✅ **Accessibility (Aksesibilitas)**
- Color contrast yang memenuhi standar WCAG 2.1 AA
- Responsive design untuk semua ukuran layar
- Semantic HTML untuk screen reader support

---

## 2. Design System

### 2.1 Color Palette

#### **Primary Colors**
Sistem AKSARA menggunakan Indigo sebagai warna utama yang merepresentasikan profesionalitas dan kredibilitas akademik.

| Color Name | Hex Code | CSS Variable | Usage |
|------------|----------|--------------|-------|
| Primary | `#4338ca` | `--primary` | Buttons, links, active states |
| Primary 600 | `#4f46e5` | `--primary-600` | Hover states, gradients |
| Primary 700 | `#3730a3` | `--primary-700` | Pressed states, dark gradients |

**Screenshot Placeholder:**
```
[INSERT: Color palette swatch showing primary colors]
```

#### **Semantic Colors**

| Purpose | Color | Hex Code | Usage |
|---------|-------|----------|-------|
| Success | Green | `#10b981` | Status "Lolos", "Selesai" |
| Warning | Yellow/Amber | `#f59e0b` | Status "Lolos Perlu Revisi", Alerts |
| Error | Red | `#ef4444` | Status "Tidak Lolos", Error messages |
| Info | Blue | `#3b82f6` | Status "Diusulkan", Informational messages |
| Neutral | Gray | `#6b7280` | Text, borders, backgrounds |

#### **Status Badge Colors**

| Status | Background | Text | Visual |
|--------|-----------|------|--------|
| Diusulkan | `#dbeafe` (blue-100) | `#1e40af` (blue-800) | 🔵 |
| Tidak Lolos | `#fee2e2` (red-100) | `#991b1b` (red-800) | 🔴 |
| Lolos Perlu Revisi | `#fef3c7` (yellow-100) | `#92400e` (yellow-800) | 🟡 |
| Lolos | `#d1fae5` (green-100) | `#065f46` (green-800) | 🟢 |
| Revisi Pra-final | `#fed7aa` (orange-100) | `#9a3412` (orange-800) | 🟠 |
| Selesai | `#d1fae5` (emerald-100) | `#065f46` (emerald-800) | ✅ |

**Screenshot Placeholder:**
```
[INSERT: Status badges showcase with all variants]
```

### 2.2 Typography

#### **Font Family**
- **Primary Font**: Figtree (Google Fonts)
- **Fallback**: System UI fonts (San Francisco, Segoe UI, Roboto)

#### **Type Scale**

| Element | Size | Weight | Line Height | Usage |
|---------|------|--------|-------------|-------|
| Heading 1 | 36px (2.25rem) | 700 (Bold) | 1.2 | Page titles |
| Heading 2 | 30px (1.875rem) | 700 (Bold) | 1.3 | Section titles |
| Heading 3 | 24px (1.5rem) | 600 (Semibold) | 1.4 | Card headers |
| Body Large | 18px (1.125rem) | 400 (Regular) | 1.5 | Intro text |
| Body | 16px (1rem) | 400 (Regular) | 1.5 | Default text |
| Body Small | 14px (0.875rem) | 400 (Regular) | 1.4 | Helper text |
| Caption | 12px (0.75rem) | 500 (Medium) | 1.3 | Labels, badges |

**Screenshot Placeholder:**
```
[INSERT: Typography hierarchy sample]
```

### 2.3 Spacing & Layout

#### **Spacing Scale (Tailwind)**
Sistem menggunakan spacing scale Tailwind CSS dengan base 4px (0.25rem):

| Token | Value | Usage Example |
|-------|-------|---------------|
| 1 | 4px | Icon padding |
| 2 | 8px | Tight spacing |
| 3 | 12px | Component padding |
| 4 | 16px | Card padding |
| 6 | 24px | Section spacing |
| 8 | 32px | Large gaps |
| 12 | 48px | Page margins |

#### **Grid System**
- **Container Max Width**: 1280px (max-w-7xl)
- **Responsive Grid**: 1, 2, 3, 4 columns based on breakpoint
- **Gap**: 12px - 24px depending on context

### 2.4 Border Radius

| Element | Radius | Usage |
|---------|--------|-------|
| Small | 4px (rounded) | Badges, small buttons |
| Medium | 8px (rounded-lg) | Inputs, cards |
| Large | 12px (rounded-xl) | Panels, modals |
| Extra Large | 16px (rounded-2xl) | Hero cards, featured sections |
| Full | 9999px (rounded-full) | Avatar, pill buttons |

### 2.5 Shadows

| Level | Tailwind Class | Usage |
|-------|---------------|-------|
| Small | shadow-sm | Subtle elevation |
| Medium | shadow-md | Cards, dropdowns |
| Large | shadow-lg | Modals, popovers |
| Extra Large | shadow-xl | Hero sections |

---

## 3. Layout & Navigation

### 3.1 Layout Architecture

#### **Layout Types**

**A. Public Layout (Guest)**
- Simple navbar dengan logo dan menu navigasi
- Full-width content area
- Footer dengan informasi kontak

**B. Authenticated Layout (Admin & Dosen)**
- Top navigation bar dengan user dropdown
- Sidebar navigation (collapsible pada mobile)
- Main content area dengan breadcrumb
- No footer (maximized workspace)

**Screenshot Placeholder:**
```
[INSERT: Layout comparison - Public vs Authenticated]
```

### 3.2 Navigation Structure

#### **A. Top Navigation Bar**

**Elemen:**
- Logo AKSARA (kiri atas)
- Search bar (center) - *future feature*
- User profile dropdown (kanan atas)
- Notification bell (kanan atas) - *future feature*

**Screenshot Placeholder:**
```
[INSERT: Top navigation bar - desktop view]
```

#### **B. Sidebar Navigation**

**Admin Sidebar Menu:**
```
📊 Dashboard
📝 Penelitian
  ↳ Semua Penelitian
  ↳ Menunggu Verifikasi
🤝 Pengabdian
  ↳ Semua Pengabdian
  ↳ Menunggu Verifikasi
📢 Informasi/Berita
  ↳ Semua Berita
  ↳ Buat Baru
📈 Laporan
  ↳ Laporan Umum
  ↳ Perbandingan Tahunan
  ↳ Export Data
```

**Dosen Sidebar Menu:**
```
📊 Dashboard
📝 Penelitian Saya
  ↳ Daftar Penelitian
  ↳ Tambah Baru
🤝 Pengabdian Saya
  ↳ Daftar Pengabdian
  ↳ Tambah Baru
📰 Informasi/Berita
📈 Laporan Pribadi
  ↳ Laporan Saya
  ↳ Perbandingan Tahunan
```

**Screenshot Placeholder:**
```
[INSERT: Sidebar navigation - Admin vs Dosen comparison]
```

#### **Sidebar Specifications:**
- **Width**: 256px (desktop), full-screen overlay (mobile)
- **Background**: White with subtle shadow
- **Active State**: Indigo background with white text
- **Hover State**: Light gray background
- **Icon Size**: 20px x 20px
- **Text**: 14px, medium weight

### 3.3 Breadcrumb Navigation

**Format:**
```
Dashboard > Penelitian > Detail: [Judul Penelitian]
```

**Screenshot Placeholder:**
```
[INSERT: Breadcrumb example]
```

### 3.4 Responsive Behavior

#### **Desktop (≥1024px)**
- Full sidebar visible
- Grid layout: 4 columns for stats cards
- Table view for data lists

#### **Tablet (768px - 1023px)**
- Collapsible sidebar (hamburger menu)
- Grid layout: 2-3 columns for cards
- Table view dengan horizontal scroll

#### **Mobile (<768px)**
- Hamburger menu untuk sidebar
- Stacked layout: 1-2 columns
- Card view untuk data lists (replace table)

**Screenshot Placeholder:**
```
[INSERT: Responsive layout - Desktop, Tablet, Mobile side by side]
```

---

## 4. Components Library

### 4.1 Buttons

#### **Primary Button**
- **Style**: Solid indigo background, white text
- **Hover**: Darker indigo background
- **Size**: Small (32px), Medium (40px), Large (48px)
- **Border Radius**: 8px (rounded-lg)

**Code Example:**
```html
<button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
    Simpan
</button>
```

**Screenshot Placeholder:**
```
[INSERT: Button variants - Primary, Secondary, Danger, Disabled]
```

#### **Secondary Button**
- **Style**: White background, gray border, gray text
- **Hover**: Light gray background

#### **Danger Button**
- **Style**: Red background, white text
- **Usage**: Delete, reject actions

#### **Button States:**
- Default
- Hover
- Active (pressed)
- Disabled (opacity 50%, cursor not-allowed)
- Loading (spinner icon)

### 4.2 Form Elements

#### **Text Input**
- **Height**: 42px
- **Border**: 1px solid gray-300
- **Border Radius**: 8px
- **Focus**: Indigo border, ring effect
- **Error State**: Red border, error message below

**Screenshot Placeholder:**
```
[INSERT: Form input states - Default, Focus, Error, Disabled]
```

#### **Select Dropdown**
- Same style as text input
- Chevron icon (right side)
- Dropdown panel dengan shadow-lg

#### **File Upload**
- Drag & drop area dengan dashed border
- File type validation feedback
- Progress bar untuk upload
- Preview untuk images

**Screenshot Placeholder:**
```
[INSERT: File upload component with drag-drop area]
```

#### **Textarea**
- Min height: 120px
- Resizable vertical
- Character counter (optional)

### 4.3 Cards

#### **Stat Card**
- **Padding**: 24px
- **Background**: White or gradient (for highlights)
- **Shadow**: shadow-md
- **Border**: 1px solid gray-200
- **Border Radius**: 16px (rounded-2xl)

**Elemen:**
- Icon (top-right or left)
- Label (small gray text)
- Value (large bold number)
- Sub-text (optional context)

**Screenshot Placeholder:**
```
[INSERT: Stat card examples - plain and gradient variants]
```

#### **Content Card**
- Used for penelitian/pengabdian items
- Header with title and status badge
- Body with metadata (tahun, tim, dll)
- Footer with action buttons

**Screenshot Placeholder:**
```
[INSERT: Content card - penelitian/pengabdian example]
```

### 4.4 Status Badges

**Design:**
- Pill shape (rounded-full)
- Padding: 4px 12px
- Font size: 12px
- Font weight: Medium (500)

**Screenshot Placeholder:**
```
[INSERT: All status badge variants in a row]
```

### 4.5 Tables

#### **Desktop Table**
- Full-width with horizontal scroll if needed
- Zebra striping (alternating row colors)
- Hover state: light blue background
- Sticky header pada scroll

**Columns:**
- Checkbox (select)
- Data columns (left-aligned text, right-aligned numbers)
- Actions column (right-aligned)

**Screenshot Placeholder:**
```
[INSERT: Data table with hover state and actions]
```

#### **Mobile Card View**
- Replace table dengan stacked cards
- Each row becomes a card
- Action button at bottom of card

**Screenshot Placeholder:**
```
[INSERT: Mobile card view as table replacement]
```

### 4.6 Modals

**Structure:**
- Overlay backdrop (black with 50% opacity)
- Modal panel: center screen, max-width 600px
- Header with title and close (X) button
- Body with scrollable content
- Footer with action buttons (right-aligned)

**Animation:**
- Fade in overlay
- Scale up modal from 95% to 100%

**Screenshot Placeholder:**
```
[INSERT: Modal example - confirmation dialog]
```

### 4.7 Toast Notifications

**Position**: Top-right corner
**Duration**: 3-5 seconds auto-dismiss
**Types**:
- Success (green)
- Error (red)
- Warning (yellow)
- Info (blue)

**Elemen:**
- Icon (left)
- Message text (center)
- Close button (right)

**Screenshot Placeholder:**
```
[INSERT: Toast notification examples - all types]
```

### 4.8 Empty States

**Design:**
- Icon atau illustration (center)
- Heading text
- Description text
- Call-to-action button

**Screenshot Placeholder:**
```
[INSERT: Empty state example - no data available]
```

### 4.9 Loading States

**Spinner:**
- Indigo color
- Size: 24px, 32px, 48px variants
- Animation: rotate 360deg in 1s

**Skeleton Screens:**
- Gray animated gradient (pulse effect)
- Match layout of actual content

**Screenshot Placeholder:**
```
[INSERT: Loading states - spinner and skeleton]
```

---

## 5. Key Screens

### 5.1 Landing Page (Public)

#### **Layout Sections:**

**A. Hero Section**
- Large heading: "AKSARA - Sistem Manajemen Penelitian & Pengabdian"
- Subtitle: Deskripsi singkat sistem
- CTA buttons: "Login Portal" & "Hubungi Kami"
- Background: Gradient indigo or image

**Screenshot Placeholder:**
```
[INSERT: Landing page - Hero section full width]
```

**B. Statistics Section**
- 4 stat cards in grid:
  - Total Penelitian Aktif
  - Total Penelitian Selesai
  - Total Pengabdian Aktif
  - Total Pengabdian Selesai

**Screenshot Placeholder:**
```
[INSERT: Landing page - Statistics section]
```

**C. Featured News Section**
- Title: "Berita & Informasi Terkini"
- 3 news cards dengan image thumbnail
- "Lihat Semua" link

**Screenshot Placeholder:**
```
[INSERT: Landing page - Featured news cards]
```

**D. Visi Misi Section**
- "Tentang LPPM"
- Brief text about vision & mission
- "Selengkapnya" button

**E. Footer**
- LPPM contact info
- Social media links
- Copyright text

### 5.2 Login & Registration

#### **Login Page**
- Center-aligned form card
- Logo at top
- Email & password fields
- "Remember me" checkbox
- "Lupa Password?" link
- "Login" button (full width)
- "Belum punya akun? Daftar" link

**Screenshot Placeholder:**
```
[INSERT: Login page - full screen]
```

#### **Registration Page (Dosen)**
- Multi-step form atau single page
- Fields: Nama, Email, NIDN/NIP, Password, Konfirmasi Password
- Terms & conditions checkbox
- "Daftar" button
- "Sudah punya akun? Login" link

**Screenshot Placeholder:**
```
[INSERT: Registration page - form layout]
```

### 5.3 Dashboard Admin

#### **Layout:**
- Welcome card dengan nama admin
- Alert section (kegiatan menunggu verifikasi, dokumen kurang)
- Quick action buttons (5 items): Kelola Penelitian, Kelola Pengabdian, Buat Berita, Lihat Laporan, Analitik
- Enhanced stats cards (4 items): Total Dosen, Total Kegiatan, Menunggu Verifikasi, Selesai Bulan Ini
- Status breakdown (2 grids): Penelitian & Pengabdian dengan 6 status masing-masing
- Recent activity timeline (10 items terakhir)
- Top 10 dosen produktif table

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - full page overview]
```

**Detailed Sections:**

**A. Alert Section**
```
⚠️ [Warning Icon] 12 kegiatan menunggu verifikasi
   Terdapat 12 pengajuan kegiatan yang memerlukan review Anda.
   [Lihat Detail →]
```

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Alert cards]
```

**B. Quick Actions**
5 button cards dengan icon dan label

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Quick action buttons]
```

**C. Stats Cards**
4 gradient cards dengan icon, label, dan value

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Stats cards]
```

**D. Status Breakdown**
2 panels (Penelitian & Pengabdian) dengan 6 stat items masing-masing

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Status breakdown panels]
```

**E. Recent Activity Timeline**
List dengan icon status, judul kegiatan, nama dosen, timestamp

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Recent activity timeline]
```

**F. Top Researchers Table**
Table dengan kolom: Rank, Nama Dosen, Total Kegiatan, Selesai, Success Rate

**Screenshot Placeholder:**
```
[INSERT: Admin dashboard - Top researchers table]
```

### 5.4 Dashboard Dosen

#### **Layout:**
- Welcome card dengan nama dosen
- Action required widget (jika ada revisi atau dokumen kurang)
- Quick action buttons (4 items): Penelitian Baru, Pengabdian Baru, Lihat Laporan, Perbandingan
- Enhanced stats cards (4 items): Total Kegiatan, Tingkat Keberhasilan, Menunggu Verifikasi, Perlu Revisi
- Status breakdown (2 panels): Penelitian & Pengabdian
- Recent activity (10 items)

**Screenshot Placeholder:**
```
[INSERT: Dosen dashboard - full page overview]
```

**Key Differences from Admin:**
- Personal stats only (tidak ada data dosen lain)
- Action Required widget prominent jika ada task
- Focus pada "What to do next"

**Screenshot Placeholder:**
```
[INSERT: Dosen dashboard - Action required widget highlighted]
```

### 5.5 Penelitian/Pengabdian List

#### **Admin View:**
- Page title dengan total count
- Filter bar: Tahun, Status, Dosen
- Search bar
- Table/Grid view toggle
- Pagination (15 items per page)

**Table Columns:**
- ID
- Judul
- Dosen
- Tahun
- Status (badge)
- Dokumen (icon indicator)
- Actions (View, Edit, Delete)

**Screenshot Placeholder:**
```
[INSERT: Penelitian list - Admin view with filters]
```

#### **Dosen View:**
- Similar layout tapi hanya data pribadi
- "Tambah Baru" button prominent (top-right)
- Filter: Tahun, Status

**Screenshot Placeholder:**
```
[INSERT: Penelitian list - Dosen view]
```

### 5.6 Form Input (Create/Edit Penelitian/Pengabdian)

#### **Layout:**
- Page title: "Tambah Penelitian Baru" atau "Edit Penelitian"
- Form card dengan sections:

**Section 1: Informasi Dasar**
- Judul (text input, required)
- Tahun (year picker, required)
- Tim Peneliti/Pelaksana (dynamic input list, add/remove)
- Sumber Dana / Lokasi (text input, required)
- Mitra (khusus pengabdian)

**Section 2: Upload Dokumen**
- Proposal (file upload, required untuk status "diusulkan")
- Laporan Akhir (file upload, required untuk status "lolos")
- Dokumen Pendukung (multiple files, optional)

**Action Buttons:**
- "Batal" (secondary button, kiri)
- "Simpan sebagai Draft" (secondary button, tengah) - *future feature*
- "Submit" (primary button, kanan)

**Screenshot Placeholder:**
```
[INSERT: Form create penelitian - full form]
```

**Validation:**
- Real-time validation dengan error message
- Required field indicator (red asterisk)
- File type & size validation

**Screenshot Placeholder:**
```
[INSERT: Form validation - error states]
```

### 5.7 Detail Penelitian/Pengabdian

#### **Layout Sections:**

**A. Header**
- Judul kegiatan (large heading)
- Status badge (prominent, kanan atas)
- Action buttons:
  - Admin: "Edit Data", "Ubah Status", "Hapus"
  - Dosen: "Edit" (conditional), "Upload Dokumen", "Hapus" (conditional)

**Screenshot Placeholder:**
```
[INSERT: Detail penelitian - Header section]
```

**B. Informasi Kegiatan Card**
- Metadata grid:
  - Tahun: [value]
  - Dosen: [nama]
  - Tim Peneliti: [list]
  - Sumber Dana: [value]
  - Status: [badge]
  - Dibuat: [timestamp]
  - Diupdate: [timestamp]

**Screenshot Placeholder:**
```
[INSERT: Detail penelitian - Info card]
```

**C. Dokumen Section**
- Title: "Dokumen Terlampir"
- Document cards with:
  - Icon (file type)
  - Nama dokumen
  - Jenis dokumen (badge)
  - Upload date
  - Version indicator
  - Actions: Download, Delete (conditional), View Versions

**Screenshot Placeholder:**
```
[INSERT: Detail penelitian - Documents section]
```

**D. Status History Timeline**
- Vertical timeline dengan:
  - Icon status
  - Status label
  - Catatan verifikasi (jika ada)
  - Admin yang mengubah
  - Timestamp

**Screenshot Placeholder:**
```
[INSERT: Detail penelitian - Status history timeline]
```

**E. Catatan Verifikasi**
- Card dengan background highlight (yellow untuk revisi)
- Icon alert
- Catatan dari admin
- Timestamp

**Screenshot Placeholder:**
```
[INSERT: Detail penelitian - Verification notes card]
```

### 5.8 Verifikasi Page (Admin)

#### **Layout:**
- Detail kegiatan (read-only)
- Document preview panel
- Verification form:
  - Status selection (dropdown dengan allowed transitions)
  - Catatan verifikasi (textarea, required)
  - "Kirim Email Notifikasi" checkbox
  - "Simpan" button

**Screenshot Placeholder:**
```
[INSERT: Verifikasi page - Admin view with form]
```

**Modal Confirmation:**
- "Apakah Anda yakin ingin mengubah status?"
- Old status → New status
- Preview catatan
- "Batal" & "Ya, Ubah Status" buttons

**Screenshot Placeholder:**
```
[INSERT: Verifikasi - Confirmation modal]
```

### 5.9 Informasi/Berita Management

#### **Admin - Create/Edit Berita:**

**Form Sections:**
- Judul (text input)
- Slug (auto-generated, editable)
- Thumbnail (image upload dengan preview)
- Konten (rich text editor atau textarea)
- Kategori (select: Penelitian, Pengabdian, Umum)
- Visibility (select: Admin, Dosen, Semua)
- Tanggal Publikasi (date picker)
- Actions: "Simpan Draft", "Publish"

**Screenshot Placeholder:**
```
[INSERT: Create berita - Admin form]
```

#### **Dosen/Public - List Berita:**

**Layout:**
- Grid cards (3 columns desktop, 1 column mobile)
- Each card:
  - Thumbnail image
  - Kategori badge
  - Judul (truncated)
  - Excerpt (2-3 lines)
  - Published date
  - "Baca Selengkapnya" link

**Screenshot Placeholder:**
```
[INSERT: Berita list - Grid card layout]
```

#### **Detail Berita:**

**Layout:**
- Hero image (full-width)
- Metadata: Kategori, Published date
- Title (large heading)
- Full content
- "Kembali ke Daftar" link

**Screenshot Placeholder:**
```
[INSERT: Detail berita - Full article view]
```

### 5.10 Laporan (Reports)

#### **Filter Section:**
- Tahun (multi-select atau range)
- Status (multi-select)
- Dosen (multi-select, admin only)
- "Terapkan Filter" button
- "Reset" button

**Screenshot Placeholder:**
```
[INSERT: Laporan - Filter bar]
```

#### **Summary Cards:**
- 4 stat cards: Total Kegiatan, Lolos, Selesai, Diusulkan
- Breakdown penelitian vs pengabdian

**Screenshot Placeholder:**
```
[INSERT: Laporan - Summary statistics]
```

#### **Data Table:**
- Sortable columns
- Export buttons: "Export PDF", "Export CSV"

**Screenshot Placeholder:**
```
[INSERT: Laporan - Data table with export buttons]
```

#### **Comparison Report (Chart):**
- Bar chart: Kegiatan per tahun
- Line chart: Trend success rate
- Pie chart: Distribusi status

**Screenshot Placeholder:**
```
[INSERT: Laporan perbandingan - Charts visualization]
```

### 5.11 Profile Page

**Layout:**
- Avatar (upload/change photo)
- Form fields:
  - Nama
  - Email
  - NIDN/NIP (read-only)
  - Institusi
  - Kewarganegaraan
- "Simpan Perubahan" button

**Separate Section: Change Password**
- Password lama
- Password baru
- Konfirmasi password baru
- "Ubah Password" button

**Screenshot Placeholder:**
```
[INSERT: Profile page - Edit form and change password]
```

---

## 6. User Flows

### 6.1 Authentication Flow

#### **A. Login Flow**
```
1. User → Landing Page
2. Click "Login" button
3. → Login Page
4. Input email & password
5. Click "Login"
6. [Valid] → Dashboard (Admin/Dosen based on role)
7. [Invalid] → Error message, stay on Login Page
```

**Screenshot Placeholder:**
```
[INSERT: Login flow diagram or step-by-step screens]
```

#### **B. Registration Flow (Dosen)**
```
1. User → Landing Page or Login Page
2. Click "Daftar" link
3. → Registration Page
4. Fill form: Nama, Email, NIDN, Password
5. Accept terms & conditions
6. Click "Daftar"
7. → Email Verification Page (info message)
8. Check email → Click verification link
9. → Email Verified Success Page
10. → Login Page
```

**Screenshot Placeholder:**
```
[INSERT: Registration flow - multi-step visualization]
```

#### **C. Forgot Password Flow**
```
1. User → Login Page
2. Click "Lupa Password?"
3. → Forgot Password Page
4. Input email
5. Click "Kirim Link Reset"
6. → Confirmation Page
7. Check email → Click reset link
8. → Reset Password Page
9. Input new password (2x)
10. Click "Reset Password"
11. → Success message
12. → Login Page
```

### 6.2 Dosen - Submit Penelitian Flow

```
1. Dosen → Dashboard
2. Click "Penelitian Baru" or Sidebar → Penelitian → Tambah
3. → Form Create Penelitian
4. Fill required fields: Judul, Tahun, Tim, Sumber Dana
5. Upload Proposal (mandatory)
6. Click "Submit"
7. [Validation Pass]
   → Success toast notification
   → Redirect to Detail Penelitian
   Status: "Diusulkan"
8. [Validation Fail]
   → Error messages on form
   → Stay on form page
```

**Screenshot Placeholder:**
```
[INSERT: Submit penelitian flow - step by step]
```

### 6.3 Admin - Verifikasi Penelitian Flow

```
1. Admin → Dashboard
2. See alert: "12 kegiatan menunggu verifikasi"
3. Click "Lihat Detail"
4. → Penelitian List (filtered by status "diusulkan")
5. Click "View" on a penelitian
6. → Detail Penelitian page
7. Review: Info, Documents, check completeness
8. Click "Ubah Status" button
9. → Verification Modal opens
10. Select new status: "Lolos" / "Tidak Lolos" / "Lolos Perlu Revisi"
11. Input catatan verifikasi (mandatory)
12. Check "Kirim email notifikasi"
13. Click "Simpan"
14. → Confirmation dialog
15. Click "Ya, Ubah Status"
16. → Status updated
17. → Success toast
18. → Page refreshes, new status displayed
19. Email sent to dosen (background)
```

**Screenshot Placeholder:**
```
[INSERT: Admin verifikasi flow - complete workflow]
```

### 6.4 Dosen - Upload Laporan Akhir Flow

```
1. Dosen → Dashboard
2. See "Action Required": "2 kegiatan perlu upload laporan akhir"
3. Click link or → Penelitian List
4. Filter/find penelitian with status "Lolos"
5. Click "View" → Detail Penelitian
6. See alert: "Laporan akhir belum diupload"
7. Click "Upload Dokumen" button
8. → Upload Modal opens
9. Select jenis: "Laporan Akhir"
10. Choose file (PDF/DOC, max 10MB)
11. Click "Upload"
12. [Validation Pass]
    → Upload progress bar
    → Success message
    → Modal closes
    → Document appears in list
13. [Validation Fail]
    → Error message in modal
14. Document uploaded, dapat request verifikasi lagi
```

**Screenshot Placeholder:**
```
[INSERT: Upload laporan flow - modal and success state]
```

### 6.5 Public - Download Laporan Flow

```
1. Visitor → Landing Page
2. Click "Unduh" in navbar
3. → Downloads Page
4. See list of finished penelitian & pengabdian
5. Apply filters: Kategori (Penelitian/Pengabdian), Tahun
6. Browse list
7. Click "Download" on desired document
8. → File download starts
9. View/save PDF locally
```

**Screenshot Placeholder:**
```
[INSERT: Public download flow - from list to download]
```

---

## 7. Interactive Elements

### 7.1 Hover States

#### **Buttons**
- Background color darken 10%
- Shadow increase (shadow-md → shadow-lg)
- Transform scale(1.02) for accent buttons
- Transition: 150ms ease-in-out

**Screenshot Placeholder:**
```
[INSERT: Button hover states comparison]
```

#### **Cards**
- Shadow increase (shadow-md → shadow-lg)
- Border color change (optional)
- Cursor: pointer (if clickable)

#### **Links**
- Text color darken
- Underline appears
- Text decoration color matches brand

### 7.2 Focus States

**All Interactive Elements:**
- Outline: 2px solid indigo-600
- Outline offset: 2px
- Remove default browser outline
- Accessible for keyboard navigation

**Screenshot Placeholder:**
```
[INSERT: Focus states - input, button, link examples]
```

### 7.3 Loading States

#### **Button Loading**
- Disabled state (opacity 70%)
- Spinner icon (rotating animation)
- Text changes: "Menyimpan..." or "Loading..."

**Screenshot Placeholder:**
```
[INSERT: Button loading state with spinner]
```

#### **Page Loading**
- Full-page overlay dengan spinner (center)
- Or skeleton screens matching content structure

**Screenshot Placeholder:**
```
[INSERT: Page loading - skeleton screen example]
```

#### **Lazy Loading (Images/Lists)**
- Fade-in animation saat content loaded
- Placeholder blur effect

### 7.4 Transitions & Animations

#### **Page Transitions**
- Fade in: opacity 0 → 1 (300ms)
- Slide up: translateY(20px) → 0 (300ms)

#### **Modal Animations**
- Backdrop: opacity 0 → 0.5 (200ms)
- Panel: scale(0.95) → 1 + opacity 0 → 1 (200ms)

#### **Toast Notifications**
- Slide in from right: translateX(100%) → 0 (300ms)
- Auto-dismiss after 4s with fade out

#### **Micro-interactions**
- Icon bounce on hover
- Button press (scale down slightly)
- Success checkmark animation (draw path)

**Screenshot Placeholder:**
```
[INSERT: Animation examples - before and after states]
```

### 7.5 Form Validation Feedback

#### **Real-time Validation**
- Error state triggered on blur or submit
- Red border + red text error message below field
- Icon indicator (X for error, ✓ for valid)

**Error Messages:**
- "Email tidak valid"
- "Password minimal 8 karakter"
- "File terlalu besar (max 10MB)"
- "Format file tidak didukung"

**Screenshot Placeholder:**
```
[INSERT: Form validation - inline error messages]
```

#### **Success State**
- Green border + green text (optional)
- Checkmark icon

### 7.6 Confirmation Dialogs

**Usage:**
- Delete actions
- Status changes
- Logout

**Layout:**
- Icon (warning for destructive actions)
- Heading: "Apakah Anda yakin?"
- Description text
- "Batal" (secondary) & "Ya, [Action]" (primary/danger) buttons

**Screenshot Placeholder:**
```
[INSERT: Confirmation dialog - delete example]
```

### 7.7 Tooltips

**Trigger:** Hover or focus
**Appearance:** Small popup dengan arrow pointing to element
**Content:** Short helpful text (max 1-2 lines)
**Position:** Top, bottom, left, or right (auto-adjust based on viewport)

**Screenshot Placeholder:**
```
[INSERT: Tooltip examples on icons and buttons]
```

### 7.8 Dropdown Menus

**User Profile Dropdown:**
- Trigger: Click avatar/name
- Panel: White card dengan shadow-lg
- Items: Profile, Settings, Logout
- Hover: Light blue background
- Divider between groups

**Screenshot Placeholder:**
```
[INSERT: Dropdown menu - user profile]
```

**Filter Dropdown:**
- Multi-select dengan checkboxes
- Search bar (for long lists)
- "Apply" & "Clear" buttons

---

## 8. Responsive Design

### 8.1 Breakpoint Strategy

| Breakpoint | Screen Width | Target Device | Layout Changes |
|------------|-------------|---------------|----------------|
| Mobile (sm) | < 640px | Smartphones | 1 column, stacked layout, hamburger menu |
| Tablet (md) | 640px - 1023px | Tablets | 2 columns, collapsible sidebar |
| Desktop (lg) | 1024px - 1279px | Laptops | 3-4 columns, full sidebar |
| Desktop XL (xl) | ≥ 1280px | Large screens | 4 columns, full sidebar, max-width container |

### 8.2 Mobile-First Approach

**Design Philosophy:**
- Base styles untuk mobile
- Progressive enhancement untuk larger screens
- Touch-friendly targets (min 44x44px)

### 8.3 Responsive Components

#### **Navigation**

**Mobile (<640px):**
- Hamburger menu icon (top-left)
- Logo (center)
- User avatar (top-right)
- Full-screen sidebar overlay on menu open
- Bottom navigation bar (optional for quick access)

**Screenshot Placeholder:**
```
[INSERT: Mobile navigation - closed and open states]
```

**Tablet (640px - 1023px):**
- Same as mobile but sidebar slides in from left (not full-screen)
- More spacing in layout

**Desktop (≥1024px):**
- Permanent sidebar (left)
- Top navigation bar
- Full layout visible

**Screenshot Placeholder:**
```
[INSERT: Navigation responsive comparison - mobile, tablet, desktop]
```

#### **Data Tables**

**Mobile:**
- Transform table to stacked cards
- Each row = one card
- Important columns only (hide less critical data)
- "View Detail" button for full info

**Screenshot Placeholder:**
```
[INSERT: Table to card transformation - mobile view]
```

**Tablet:**
- Horizontal scrollable table
- Sticky first column (optional)

**Desktop:**
- Full table visible
- All columns displayed

#### **Forms**

**Mobile:**
- Full-width inputs
- Stacked labels (top)
- Single column layout
- Larger touch targets

**Screenshot Placeholder:**
```
[INSERT: Form responsive - mobile vs desktop layout]
```

**Desktop:**
- Two-column layout (where appropriate)
- Side-by-side labels (optional)
- More compact spacing

#### **Stat Cards Grid**

**Mobile:** 1-2 columns
**Tablet:** 2-3 columns
**Desktop:** 4 columns

**Screenshot Placeholder:**
```
[INSERT: Stat cards grid - responsive behavior]
```

### 8.4 Typography Scaling

| Element | Mobile (sm) | Tablet (md) | Desktop (lg) |
|---------|-------------|-------------|--------------|
| H1 | 24px (1.5rem) | 30px (1.875rem) | 36px (2.25rem) |
| H2 | 20px (1.25rem) | 24px (1.5rem) | 30px (1.875rem) |
| H3 | 18px (1.125rem) | 20px (1.25rem) | 24px (1.5rem) |
| Body | 14px (0.875rem) | 16px (1rem) | 16px (1rem) |

### 8.5 Touch Optimization

**Mobile Interactions:**
- Minimum touch target: 44x44px (iOS guideline)
- Increased spacing between clickable elements
- Swipe gestures (optional):
  - Swipe left on list item → Show actions
  - Pull to refresh (list pages)
  - Swipe between tabs

**Screenshot Placeholder:**
```
[INSERT: Touch targets - size comparison]
```

### 8.6 Performance Optimization

**Mobile-Specific:**
- Lazy loading images (below fold)
- Reduced animation complexity
- Smaller image sizes (responsive images with srcset)
- Minimize initial page load size

---

## 9. Accessibility & Usability

### 9.1 WCAG 2.1 Compliance

**Level AA Target:**
- Color contrast ratio: 4.5:1 minimum for normal text
- Color contrast ratio: 3:1 minimum for large text (18px+)
- All functionality available via keyboard
- Screen reader compatible

### 9.2 Color Contrast

**Primary Text on White:**
- Gray-900 (#111827): 16.75:1 ✅ Pass AAA
- Gray-700 (#374151): 10.73:1 ✅ Pass AAA
- Indigo-600 (#4f46e5): 8.33:1 ✅ Pass AA

**Status Badge Contrast:**
- All badge combinations tested: Pass AA minimum

**Screenshot Placeholder:**
```
[INSERT: Color contrast checker results]
```

### 9.3 Keyboard Navigation

**Tab Order:**
- Logical flow: top-to-bottom, left-to-right
- Skip to main content link (hidden, appears on focus)
- All interactive elements accessible

**Keyboard Shortcuts:**
- Tab: Navigate forward
- Shift+Tab: Navigate backward
- Enter/Space: Activate button/link
- Escape: Close modal/dropdown
- Arrow keys: Navigate within dropdowns/menus

**Screenshot Placeholder:**
```
[INSERT: Keyboard navigation - focus indicator visible]
```

### 9.4 Screen Reader Support

**Semantic HTML:**
- Proper heading hierarchy (H1 → H2 → H3)
- `<nav>` for navigation
- `<main>` for main content
- `<article>` for content items
- `<button>` for clickable actions (not `<div>`)

**ARIA Labels:**
- `aria-label` for icon-only buttons
- `aria-describedby` for form field errors
- `aria-live` for toast notifications
- `role="status"` for loading indicators

**Alt Text:**
- All images have descriptive alt text
- Decorative images: `alt=""` (empty)

### 9.5 Form Usability

**Best Practices Implemented:**
- ✅ Clear labels for all inputs
- ✅ Placeholder text as hint (not replacement for label)
- ✅ Error messages specific and actionable
- ✅ Required fields clearly marked (*)
- ✅ Autocomplete attributes for common fields
- ✅ Input type appropriate (email, tel, date, etc.)
- ✅ Character counter for limited fields (e.g., textarea)

**Screenshot Placeholder:**
```
[INSERT: Form best practices - labeled example]
```

### 9.6 Error Prevention & Recovery

**Strategies:**
- Confirmation dialogs for destructive actions (delete)
- Auto-save drafts (future feature)
- Undo option for certain actions (future feature)
- Clear error messages with recovery instructions
- Validation before submission (prevent server errors)

### 9.7 Help & Documentation

**Contextual Help:**
- Tooltip icons (?) next to complex fields
- Helper text below inputs
- Inline documentation links

**Example Tooltips:**
- NIDN: "Nomor Induk Dosen Nasional (10 digit)"
- Sumber Dana: "Misal: DRPM, Internal, Eksternal"

**Screenshot Placeholder:**
```
[INSERT: Contextual help - tooltip and helper text examples]
```

### 9.8 Progressive Disclosure

**Concept:**
- Show essential info first
- Hide advanced options behind "More Options" toggle
- Expand details on demand (accordion)

**Example:**
- Upload document: Basic upload → Advanced options (tags, notes) collapsed

**Screenshot Placeholder:**
```
[INSERT: Progressive disclosure - collapsed and expanded states]
```

---

## 10. Best Practices Implementation

### 10.1 Performance Best Practices

#### **Frontend Performance**
- ✅ CSS minified dan combined (Vite build)
- ✅ JavaScript lazy loading untuk non-critical scripts
- ✅ Image optimization (WebP format, lazy loading)
- ✅ Font subsetting (hanya character yang dipakai)
- ✅ Browser caching leveraged

**Metrics Target:**
- First Contentful Paint (FCP): < 1.8s
- Time to Interactive (TTI): < 3.5s
- Largest Contentful Paint (LCP): < 2.5s

#### **Backend Performance**
- Pagination untuk list data besar
- Eager loading untuk relasi Eloquent (prevent N+1)
- Database indexing pada foreign keys
- Query result caching (future feature)

### 10.2 Security Best Practices

**Frontend:**
- ✅ CSRF token pada semua form POST
- ✅ Input sanitization
- ✅ XSS prevention (escape output)
- ✅ File upload validation (MIME type & size)

**Visual Indicators:**
- HTTPS lock icon (browser default)
- Session timeout warning (future feature)

### 10.3 Usability Best Practices

#### **Feedback Principles**
- ✅ Immediate feedback untuk user actions
- ✅ Loading states untuk async operations
- ✅ Success confirmation visible dan clear
- ✅ Error messages helpful dan actionable

#### **Consistency Principles**
- ✅ Same action = same result across pages
- ✅ Same terminology throughout app
- ✅ Same button placement (e.g., "Simpan" always bottom-right)

#### **Simplicity Principles**
- ✅ Minimal cognitive load
- ✅ Clear visual hierarchy
- ✅ Progressive disclosure
- ✅ Defaults for common choices

### 10.4 Content Strategy

**Writing Guidelines:**
- Clear, concise labels
- Active voice preferred
- Consistent terminology:
  - "Penelitian" not "Research"
  - "Pengabdian" not "Community Service" (consistent Indonesian)
- Error messages specific: "Email sudah terdaftar" instead of "Error"

**Microcopy Examples:**
- Empty state: "Belum ada penelitian. Mulai dengan menambahkan penelitian baru."
- Success: "Penelitian berhasil disimpan!"
- Error: "Gagal mengupload file. Pastikan ukuran file < 10MB."

### 10.5 Future Enhancements

**Planned UI/UX Improvements:**
- 🔄 Dark mode support
- 🔄 Customizable dashboard widgets (drag-drop)
- 🔄 Advanced filtering dengan saved filters
- 🔄 Bulk actions (multi-select items)
- 🔄 Activity feed/notification center
- 🔄 Collaborative editing (real-time)
- 🔄 Mobile app (native atau PWA)
- 🔄 Chatbot helper untuk FAQ
- 🔄 Data visualization dashboards (advanced charts)
- 🔄 Gantt chart untuk timeline kegiatan

---

## 📸 Screenshot Index

**Total Screenshots Required: ~80**

### Landing Page & Public (8 screenshots)
1. Landing page - Full hero section
2. Landing page - Statistics section
3. Landing page - Featured news
4. Berita list - Grid cards
5. Detail berita - Full article
6. Downloads page - Document list
7. Visi Misi page
8. Public footer

### Authentication (6 screenshots)
9. Login page - Desktop
10. Login page - Mobile
11. Registration form
12. Email verification page
13. Forgot password page
14. Reset password page

### Admin Dashboard (10 screenshots)
15. Admin dashboard - Full page overview
16. Admin dashboard - Alert cards
17. Admin dashboard - Quick actions
18. Admin dashboard - Stats cards
19. Admin dashboard - Status breakdown
20. Admin dashboard - Recent activity
21. Admin dashboard - Top researchers table
22. Admin dashboard - Mobile view
23. Admin sidebar navigation
24. Admin top navbar

### Dosen Dashboard (6 screenshots)
25. Dosen dashboard - Full page
26. Dosen dashboard - Action required widget
27. Dosen dashboard - Stats cards
28. Dosen dashboard - Mobile view
29. Dosen sidebar navigation
30. Dosen quick actions

### Penelitian/Pengabdian (12 screenshots)
31. Penelitian list - Admin view with filters
32. Penelitian list - Dosen view
33. Penelitian list - Table view
34. Penelitian list - Card view (mobile)
35. Create penelitian - Form full
36. Create penelitian - Form validation errors
37. Detail penelitian - Header
38. Detail penelitian - Info card
39. Detail penelitian - Documents section
40. Detail penelitian - Status timeline
41. Detail penelitian - Verification notes
42. Edit penelitian form

### Verifikasi (4 screenshots)
43. Verifikasi page - Admin view
44. Verifikasi form - Status selection
45. Verifikasi - Confirmation modal
46. Verifikasi - Success state

### Informasi/Berita Management (4 screenshots)
47. Create berita - Admin form
48. Edit berita - Form with preview
49. Berita list - Admin management view
50. Berita card - Hover state

### Laporan (6 screenshots)
51. Laporan - Filter bar
52. Laporan - Summary statistics
53. Laporan - Data table
54. Laporan perbandingan - Charts
55. Export options dropdown
56. PDF export preview

### Profile & Settings (3 screenshots)
57. Profile page - Edit form
58. Profile page - Change password section
59. Profile page - Mobile view

### Components (15 screenshots)
60. Button variants - All states
61. Form inputs - All states
62. Status badges - All variants
63. Stat cards - Plain and gradient
64. Content card - Penelitian example
65. Data table - Desktop
66. Data table - Mobile cards
67. Modal - Confirmation dialog
68. Toast notifications - All types
69. Empty state example
70. Loading spinner and skeleton
71. File upload component
72. Dropdown menu - User profile
73. Tooltip examples
74. Breadcrumb navigation

### Responsive & Interaction (10 screenshots)
75. Navigation - Mobile, tablet, desktop comparison
76. Responsive layout - 3 breakpoints side-by-side
77. Table to card - Mobile transformation
78. Form - Mobile vs desktop layout
79. Stat cards - Responsive grid
80. Touch targets - Size demonstration
81. Hover states - Button comparison
82. Focus states - Accessibility
83. Animation - Before/after states
84. Keyboard navigation - Focus visible

---

## 📝 Changelog & Version History

### Version 1.0 (Current)
- Initial release dengan core features
- Responsive design untuk 3 breakpoints
- Complete design system implementation
- Accessibility AA compliance

### Planned Updates
- **v1.1**: Dark mode support
- **v1.2**: Advanced filtering & search
- **v1.3**: Mobile PWA version
- **v2.0**: Collaborative features & real-time updates

---

## 👥 Design Credits

**Design System:** Based on Tailwind CSS v3
**Icons:** Heroicons (outline & solid variants)
**Font:** Figtree from Google Fonts
**Color Inspiration:** Indigo palette from Tailwind

---

## 📞 Contact & Support

Untuk pertanyaan terkait UI/UX design atau request perubahan:
- **Email**: lppm@university.ac.id
- **Documentation**: [Internal Wiki/Confluence]
- **Figma Design File**: [Link jika ada]

---
