# AKSARA - Sistem Informasi Penelitian dan Pengabdian

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=flat&logo=laravel" alt="Laravel 12.0">
<img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP 8.2+">
<img src="https://img.shields.io/badge/License-MIT-green" alt="MIT License">
</p>

## Tentang AKSARA

AKSARA adalah sistem informasi berbasis web untuk mengelola kegiatan penelitian dan pengabdian masyarakat di lingkungan perguruan tinggi. Sistem ini menyediakan platform terintegrasi untuk:

- **Manajemen Penelitian** - Pengajuan, verifikasi, dan monitoring penelitian dosen
- **Manajemen Pengabdian** - Pengelolaan kegiatan pengabdian masyarakat
- **Workflow Verifikasi** - Sistem status multi-tahap dengan history tracking
- **Manajemen Dokumen** - Upload, versioning, dan download dokumen pendukung
- **Dashboard Analytics** - Statistik dan laporan kegiatan
- **Portal Publik** - Akses informasi penelitian dan pengabdian untuk masyarakat

## Fitur Utama

### Admin
- ✅ Verifikasi dokumen penelitian dan pengabdian
- ✅ Manajemen status dengan 5 tahapan workflow
- ✅ Manajemen profil dosen dan peneliti
- ✅ Dashboard statistik dan analytics
- ✅ Export laporan (PDF/Excel)

### Dosen
- ✅ Submit penelitian dan pengabdian
- ✅ Upload dokumen pendukung (multiple files)
- ✅ Track status verifikasi real-time
- ✅ History revisi dan catatan verifikasi
- ✅ Manajemen profil dan publikasi

### Publik
- ✅ Browse penelitian dan pengabdian yang telah selesai
- ✅ Filter dan search berdasarkan kategori
- ✅ View detail dan unduh dokumen publikasi
- ✅ Informasi peneliti dan scopus ID

## Tech Stack

- **Backend**: Laravel 12.0 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS 3.x
- **Build Tool**: Vite 5.x
- **Database**: MySQL 8.0+ / PostgreSQL 14+
- **PDF Generation**: DomPDF 3.1
- **Testing**: Pest PHP 3.x

## Requirements

- PHP >= 8.2
- Composer >= 2.6
- Node.js >= 20.x & npm >= 10.x
- MySQL >= 8.0 atau PostgreSQL >= 14
- Git

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/aksara.git
cd aksara
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aksara
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding
```bash
php artisan migrate
php artisan db:seed
```

### 5. Storage Link
```bash
php artisan storage:link
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### 8. Default Credentials

**Admin**
- Email: `admin@aksara.test`
- Password: `password`

**Dosen**
- Email: `dosen@aksara.test`
- Password: `password`

## Struktur Project

```
aksara/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/                     # Admin controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── LaporanController.php
│   │   │   └── VerifikasiController.php
│   │   ├── Dosen/                     # Dosen controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── PenelitianController.php
│   │   │   ├── PengabdianController.php
│   │   │   └── LaporanController.php
│   │   └── Public/                    # Public controllers
│   │       └── InformasiController.php
│   ├── Models/                         # Eloquent models
│   │   ├── User.php
│   │   ├── LecturerProfile.php
│   │   ├── Penelitian.php
│   │   ├── Pengabdian.php
│   │   ├── StatusHistory.php
│   │   └── DocumentVersion.php
│   ├── Services/                       # Business logic layer
│   │   ├── StatusWorkflowService.php  # Workflow management
│   │   ├── DocumentService.php        # Document handling
│   │   └── StatisticsService.php      # Analytics & stats
│   └── Exceptions/
│       └── WorkflowException.php      # Custom exceptions
├── database/
│   ├── migrations/                    # Database migrations
│   └── seeders/                       # Database seeders
├── resources/
│   ├── views/                         # Blade templates
│   │   ├── admin/                     # Admin views
│   │   ├── dosen/                     # Dosen views
│   │   └── public/                    # Public views
│   ├── css/                           # Stylesheets
│   └── js/                            # JavaScript files
├── routes/
│   ├── web.php                        # Web routes
│   └── auth.php                       # Authentication routes
├── tests/                             # Testing
│   └── Feature/
│       └── DatabaseVerificationTest.php  # Database tests (17 test cases)
└── DOKUMENTASI-PENGUJIAN-DATABASE.md  # Testing documentation
```

## Workflow Status

Sistem menggunakan 6 tahapan status verifikasi:

```
diusulkan → tidak_lolos (REJECTED)
          ↓
          → lolos_perlu_revisi → revisi_pra_final → selesai
          ↓
          → lolos → selesai
```

**Status Flow**:
1. **diusulkan** - Submission awal oleh dosen
2. **tidak_lolos** - Ditolak admin (terminal state)
3. **lolos_perlu_revisi** - Diterima dengan revisi minor
4. **lolos** - Diterima tanpa revisi (fast track)
5. **revisi_pra_final** - Tahap revisi akhir (dari lolos_perlu_revisi)
6. **selesai** - Selesai dan dapat dipublikasi

> **Note**: Status ini berlaku untuk penelitian dan pengabdian dengan ENUM constraint validation.

## Testing

### Database Verification Tests

Sistem memiliki comprehensive database testing dengan 17 test cases:

```bash
# Run all tests
php artisan test

# Run database verification tests
php artisan test --filter DatabaseVerificationTest

# Run specific test group
php artisan test --filter="test_enum_constraint|test_foreign_key"

# Run with coverage
php artisan test --coverage
```

**Test Coverage**:
- ✅ 17 test cases (100% passed)
- ✅ 66 assertions verified
- ✅ Struktur tabel (9 tables)
- ✅ Constraints (ENUM, UNIQUE, NOT NULL, Foreign Key)
- ✅ Relasi (One-to-One, One-to-Many, Polymorphic)
- ✅ CRUD operations & JSON field handling
- ✅ Document versioning

📄 **Dokumentasi Lengkap**: Lihat [DOKUMENTASI-PENGUJIAN-DATABASE.md](DOKUMENTASI-PENGUJIAN-DATABASE.md) untuk detail output setiap test.

## Database Schema

### Key Tables (9 tables verified)

- `users` - User authentication (admin, dosen, pimpinan)
- `lecturer_profiles` - Profil dosen (NIP, affiliation, citizenship)
- `penelitian` - Data penelitian dengan status workflow
- `pengabdian` - Data pengabdian dengan lokasi & mitra
- `penelitian_documents` - Dokumen penelitian (proposal, laporan_kemajuan, laporan_akhir)
- `pengabdian_documents` - Dokumen pengabdian (proposal, laporan_akhir)
- `status_history` - History perubahan status (polymorphic)
- `document_versions` - Versioning dokumen multi-type
- `informasi` - Konten informasi publik

### Constraints Implemented

- **ENUM**: `users.role` (admin/dosen/pimpinan), `status` (6 values)
- **UNIQUE**: `users.email`, `lecturer_profiles.nip`
- **Foreign Keys**: Cascade delete untuk integritas referensial
- **NOT NULL**: Field wajib tervalidasi
- **JSON Support**: `tim_peneliti`, `tim_pelaksana` (string atau JSON array)

### Documentation

- 📊 [DOKUMENTASI-PENGUJIAN-DATABASE.md](DOKUMENTASI-PENGUJIAN-DATABASE.md) - Test results & verification
- 📐 [database-erd.mermaid](database-erd.mermaid) - ER diagram lengkap (if available)

## UI/UX Design

- **Design System**: Tailwind CSS dengan custom color scheme
- **Responsive**: Mobile-first approach
- **Accessibility**: WCAG 2.1 Level AA compliance
- **Documentation**: [UI-UX-Documentation.md](UI-UX-Documentation.md)

## Security Features

- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection (Blade escaping)
- ✅ Authentication & Authorization (Laravel Breeze)
- ✅ Role-based Access Control (Middleware)
- ✅ Secure File Upload (MIME type validation)
- ✅ Password Hashing (bcrypt)
- ✅ Rate Limiting

## Deployment

### Production Checklist

1. Set environment to production:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

4. Configure web server (Nginx example):
```nginx
server {
    listen 80;
    server_name aksara.example.com;
    root /var/www/aksara/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Contributing

Kontribusi sangat diterima! Silakan:
1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Authors

- **Development Team** - Praktikum Pemrograman 2

