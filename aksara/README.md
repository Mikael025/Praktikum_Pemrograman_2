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
│   │   ├── Admin*Controller.php       # Admin controllers
│   │   ├── Dosen*Controller.php       # Dosen controllers
│   │   └── Public*Controller.php      # Public controllers
│   ├── Models/                         # Eloquent models
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
│   ├── css/                           # Stylesheets
│   └── js/                            # JavaScript files
├── routes/
│   ├── web.php                        # Web routes
│   └── auth.php                       # Authentication routes
└── tests/                             # Pest tests
```

## Workflow Status

Sistem menggunakan 5 tahapan status verifikasi:

```
diajukan → tidak_lolos (REJECTED)
        ↓
        → lolos_perlu_revisi → revisi_pra_final → selesai
        ↓
        → lolos → selesai
```

**Status Flow**:
1. **diajukan** - Submission awal oleh dosen
2. **tidak_lolos** - Ditolak admin (terminal state)
3. **lolos_perlu_revisi** - Diterima dengan revisi minor
4. **lolos** - Diterima tanpa revisi
5. **revisi_pra_final** - Tahap revisi akhir (dari lolos_perlu_revisi)
6. **selesai** - Selesai dan dapat dipublikasi

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter StatusWorkflowServiceTest

# Run with coverage
php artisan test --coverage
```

## Database Schema

Key tables:
- `users` - User authentication (admin, dosen)
- `lecturer_profiles` - Profil dosen (NIDN, Scopus ID, dll)
- `penelitian` - Data penelitian
- `pengabdian` - Data pengabdian
- `penelitian_documents` - Dokumen penelitian
- `pengabdian_documents` - Dokumen pengabdian
- `status_histories` - History perubahan status
- `document_versions` - Versioning dokumen

See [database-erd.mermaid](database-erd.mermaid) untuk ER diagram lengkap.

## UI/UX Design

- **Design System**: Tailwind CSS dengan custom color scheme
- **Responsive**: Mobile-first approach
- **Accessibility**: WCAG 2.1 Level AA compliance
- **Documentation**: [UI-UX-Documentation.md](UI-UX-Documentation.md)

## Code Quality

Current scores from [Code-Quality-Analysis.txt](Code-Quality-Analysis.txt):

- **Overall**: 78/100
- **Structure**: 75/100
- **Maintainability**: 80/100
- **Documentation**: 65/100
- **Security**: 90/100

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

