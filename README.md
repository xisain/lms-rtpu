# 📚 Learning Management System - RTPU PNJ

Sistem Manajemen Pembelajaran (LMS) terintegrasi untuk Program Pelatihan dan PEKERTI di Politeknik Negeri Jakarta (PNJ). Platform ini dirancang untuk mendukung pembelajaran online yang interaktif dengan fitur manajemen kursus, penilaian, dan tracking progres peserta.

<img src="https://github.com/xisain/lms-rtpu/blob/testing-sain/public/images/rtpu.png" alt="RTPU PNJ Logo" width="200"/>

---

## 🎯 Tentang Project

**LMS RTPU** adalah platform pembelajaran digital yang komprehensif yang memfasilitasi:
- 📖 **Manajemen Kursus**: Kelola materi pembelajaran dengan struktur berjenjang (Materi → Sub Materi)
- 👥 **Manajemen Peserta**: Pendaftaran, enrollment, dan tracking progres pembelajaran
- 📝 **Sistem Penilaian**: Quiz, tugas akhir, dan sistem rubrik penilaian
- 💳 **Manajemen Pembayaran**: Integrasi sistem pembayaran untuk kursus berbayar
- 🎓 **Sertifikat Digital**: Otomatis generate sertifikat untuk peserta yang lulus
- 📊 **Dashboard Analytics**: Laporan dan visualisasi data pembelajaran

---

## 🚀 Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Backend** | Laravel Framework v12 |
| **Frontend** | Blade Template + Tailwind CSS v4 |
| **Database** | MySQL 8.0+ |
| **Build Tool** | Vite |
| **Authentication** | Laravel Sanctum |
| **PDF Generation** | DomPDF |
| **Queue** | Laravel Queue |
| **Testing** | PHPUnit v11 |
| **Code Formatter** | Laravel Pint |
| **PHP Version** | 8.4.14 |

---

## 📋 Fitur Utama

### 🎓 Untuk Peserta
- ✅ Daftar dan enroll ke kursus
- ✅ Akses materi pembelajaran berjenjang
- ✅ Tracking progress pembelajaran realtime
- ✅ Mengerjakan quiz dan kuis interaktif
- ✅ Submit tugas akhir dengan Google Drive integration
- ✅ Menerima feedback dan nilai dari reviewer
- ✅ Download sertifikat digital
- ✅ Lihat history pembayaran dan subscription

### 👨‍🏫 Untuk Instruktur/Dosen
- ✅ Buat dan kelola kursus
- ✅ Upload materi pembelajaran (text, video, file)
- ✅ Buat quiz dengan berbagai tipe pertanyaan
- ✅ Review dan nilai tugas akhir peserta
- ✅ Export laporan dalam format PDF
- ✅ Cek statistik pembelajaran peserta
- ✅ Kelola enroll dan pembayaran peserta

### 🔍 Fitur Review Final Task (Terbaru)
- 🎯 **Conditional Review**: Tampilkan catatan untuk **Pelatihan** atau rubrik untuk **PEKERTI**
- 📊 **Scoring**: Input nilai 0-100 dengan grading otomatis (A/B/C/D)
- ✅ **Checklist Komponen**: 19 komponen penilaian testruktur
- 📝 **Catatan Reviewer**: Feedback detail untuk setiap submission
- 🎨 **Status Tracking**: Approved, Rejected, atau Pending

### 💼 Untuk Admin
- ✅ Manajemen user (peserta, instruktur, reviewer)
- ✅ Manajemen kategori kursus
- ✅ Monitoring seluruh aktivitas platform
- ✅ Generate laporan keseluruhan

---

## 📊 Struktur Database Utama

```
├── users
│   ├── name, email, password
│   ├── role (peserta, instruktur, reviewer, admin)
│   └── relationships: profiles, enrollments, courses...
│
├── courses
│   ├── nama_course, description, price
│   ├── category_id, teacher_id, reviewer_id
│   ├── image_link, start_date, end_date
│   └── relationships: materials, enrollments, final_task...
│
├── categories
│   ├── category (nama kategori)
│   ├── type (enum: 'pelatihan', 'pekerti')
│   └── description
│
├── materials (Materi Pembelajaran)
│   ├── course_id, title, description
│   ├── content (text/html)
│   ├── order (untuk sorting)
│   └── relationships: submaterials, progress
│
├── submaterials (Sub Materi)
│   ├── material_id, title, content
│   ├── video_link (optional)
│   └── relationships: progress
│
├── quizzes & quiz_questions
│   ├── Quiz dengan berbagai tipe pertanyaan
│   ├── quiz_options untuk pilihan jawaban
│   └── quiz_attempts untuk tracking jawaban peserta
│
├── final_tasks & final_task_submissions
│   ├── final_task_submissions (submit peserta)
│   ├── final_task_reviews (penilaian dari reviewer)
│   ├── nilai (score 0-100)
│   └── catatan (feedback untuk peserta)
│
├── enrollments
│   ├── Tracking enroll peserta ke course
│   ├── Manage akses dan status
│   └── relationships: user, course
│
└── payments & subscriptions
    ├── Tracking pembayaran
    ├── Subscription management
    └── Invoice dan receipt
```

---

## 🔧 Instalasi & Setup

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### Step-by-Step Installation

1. **Clone Repository**
   ```bash
   git clone https://github.com/xisain/lms-rtpu.git
   cd lms-rtpu
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed # (Optional untuk sample data)
   ```

5. **Build Frontend Assets**
   ```bash
   npm run build
   ```

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

Server akan berjalan di `http://localhost:8000`

### Dengan Docker (Laravel Sail)
```bash
composer run dev  # Jalankan server + queue + frontend bundler sekaligus
```

---

## 📝 Konfigurasi

### Environment Variables
```env
APP_NAME=LMS-RTPU
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_rtpu
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
# ... konfigurasi email lainnya
```

---

## 🏗️ Struktur Project

```
lms-rtpu/
├── app/
│   ├── Console/Commands/         # Artisan commands
│   ├── Http/
│   │   ├── Controllers/          # Main application logic
│   │   ├── Middleware/           # HTTP middleware
│   │   ├── Requests/             # Form request validation
│   │   └── Resources/            # API resource transformation
│   ├── Jobs/                     # Queued jobs
│   ├── Mail/                     # Mail classes
│   ├── Models/                   # Eloquent models
│   ├── Providers/                # Service providers
│   └── Traits/                   # Reusable traits
│
├── bootstrap/
│   ├── app.php                   # App bootstrapping
│   └── providers.php             # Service providers config
│
├── config/                       # Configuration files
├── database/
│   ├── migrations/               # Schema migrations
│   ├── factories/                # Model factories
│   └── seeders/                  # Database seeders
│
├── public/
│   ├── storage/                  # File uploads
│   ├── images/                   # Static images
│   └── index.php                 # App entry point
│
├── resources/
│   ├── css/                      # Tailwind CSS files
│   ├── js/                       # JavaScript files
│   └── views/                    # Blade templates
│
├── routes/
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes
│   └── console.php               # Console commands
│
├── storage/                      # Cache, logs, uploads
├── tests/                        # PHPUnit tests
├── vendor/                       # Composer dependencies
│
├── .env.example                  # Example environment
├── composer.json                 # PHP dependencies
├── package.json                  # Node dependencies
├── vite.config.js                # Vite configuration
├── phpunit.xml                   # PHPUnit configuration
├── tailwind.config.js            # Tailwind configuration
└── README.md                     # Documentation
```

---

## 🚀 Available Commands

### Development
```bash
# Development server (server + queue + vite)
composer run dev

# Frontend only
npm run dev

# Build for production
npm run build

# Code formatting (Laravel Pint)
vendor/bin/pint --dirty

# Database
php artisan migrate                 # Run migrations
php artisan migrate:rollback       # Revert last migration
php artisan db:seed                # Run seeders
php artisan tinker                 # Interactive shell

# Queue
php artisan queue:listen           # Listen untuk jobs

# Testing
php artisan test                   # Run all tests
php artisan test --filter=name     # Run specific test
```

---



## 🔐 Authentication & Authorization

Project menggunakan **Laravel Sanctum** untuk API authentication dan Laravel's built-in authorization dengan **Gates & Policies**.

### User Roles
- **Admin**: Akses penuh ke seluruh sistem
- **Instruktur/Dosen**: Kelola kursus dan nilai peserta
- **Reviewer**: Review dan nilai tugas akhir
- **Peserta**: Akses kursus dan submit tugas

---



## 🎨 UI/UX Features

- **Responsive Design**: Mobile-first approach dengan Tailwind CSS v4
- **Dark Mode Support**: Toggle dark/light theme
- **Interactive Dashboard**: Real-time progress tracking
- **Sweet Alert**: User-friendly notifications
- **Smooth Animations**: Transition dan hover effects

---

## 📊 Recent Updates

### Fitur Final Task Review (v2.0)
- ✨ Conditional rendering berdasarkan tipe kategori (Pelatihan/PEKERTI)
- 📝 Catatan khusus untuk program pelatihan
- 📊 Rubrik penilaian dengan kriteria untuk PEKERTI
- 🎯 Scoring system dengan grading otomatis (A-D)
- ✅ 19 komponen penilaian checklist

---

## 🐛 Debugging & Troubleshooting

### Debugging Tools
- **Laravel Debugbar**: Development toolbar
- **Laravel Pail**: Real-time log viewer
- **Tinker**: Interactive shell
- **PhpUnit**: Automated testing

### Common Issues

1. **Vite Manifest Error**
   ```bash
   npm run build  # atau npm run dev
   ```

2. **Database Connection Error**
   - Cek `.env` database configuration
   - Pastikan MySQL running: `php artisan migrate`

3. **File Upload Issues**
   - Cek permission: `chmod -R 775 storage/ bootstrap/cache/`
   - Cek disk configuration di `config/filesystems.php`

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

Project ini dilisensikan di bawah MIT License - lihat file `LICENSE` untuk detail.

---

## 👥 Tim Pengembang

- **Fullstack**: [@xisain](https://github.com/xisain)

---

## 📞 Support & Contact

- 📧 Email: rtpu@pnj.ac.id
- 🌐 Website: https://rtpu.pnj.ac.id
- 📱 WhatsApp: [Group Link]

---


**Made with ❤️ for RTPU PNJ**
