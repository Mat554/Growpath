# GrowPath - Sistem Tes Minat Bakat

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

GrowPath adalah sistem informasi tes minat bakat berbasis web yang menggunakan metode RASEC (Realistic, Investigative, Artistic, Social, Enterprising, Conventional) untuk membantu pengguna memahami tipe kepribadian mereka.

---

## Persyaratan Sistem

Sebelum memulai, pastikan komputer Anda memenuhi persyaratan berikut:

- **PHP** versi 8.1 atau lebih baru
- **Composer** versi 2.x
- **Node.js** versi 18.x atau lebih baru
- **NPM** versi 8.x atau lebih baru
- **Database** - MySQL 8.0 atau PostgreSQL 14+
- **Web Server** - Apache dengan mod_rewrite atau Nginx
- **XAMPP** (direkomendasikan untuk Windows) atau Laravel Valet (macOS/Linux)

---

## Instalasi

Ikuti langkah-langkah berikut untuk menjalankan GrowPath secara lokal:

### 1. Clone Repository

```bash
git clone https://github.com/Mat554/Growpath.git
cd Growpath
```

### 2. Install Dependencies

Install dependencies PHP dengan Composer:

```bash
composer install
```

Install dependencies Node.js dengan NPM:

```bash
npm install
```

### 3. Buat Database

Buat database baru di MySQL atau PostgreSQL. Contoh untuk MySQL:

```sql
CREATE DATABASE growpath;
```

Atau untuk PostgreSQL:

```sql
CREATE DATABASE growpath;
```

### 4. Konfigurasi Environment

Salin file contoh environment:

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan pengaturan berikut:

```env
APP_NAME=GrowPath
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Konfigurasi Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growpath
DB_USERNAME=root
DB_PASSWORD=

# Atau untuk PostgreSQL
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=growpath
# DB_USERNAME=postgres
# DB_PASSWORD=password_anda
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migration dan Seeder

Jalankan migration untuk membuat semua tabel:

```bash
php artisan migrate
```

Jika Anda ingin menambahkan data contoh:

```bash
php artisan db:seed
```

### 7. Buat Symlink Storage

```bash
php artisan storage:link
```

### 8. Compile Assets

Compile assets Frontend dengan Vite:

```bash
npm run dev
```

Untuk production:

```bash
npm run build
```

---

## Menjalankan Aplikasi

### Menggunakan Laravel Artisan Server

Jalankan development server:

```bash
php artisan serve
```

Aplikasi akan tersedia di `http://localhost:8000`

### Menggunakan XAMPP

1. Pastikan Apache dan MySQL di XAMPP Control Panel sudah berjalan (running)
2. Pindahkan project ke folder htdocs XAMPP: `C:\xampp\htdocs\Growpath`
3. Buka browser dan akses: `http://localhost/Growpath/public`

### Menggunakan Laragon

1. Tambahkan project ke Laragon
2. Klik "Start All" di Laragon Control Panel
3. Akses project melalui menu "Web Browser"

---

## Konfigurasi Email (OTP)

GrowPath menggunakan email untuk verifikasi OTP saat login. Konfigurasi email di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=app_password_google
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@growpath.com"
MAIL_FROM_NAME="Growpath App"
```

**Catatan:** Jika menggunakan Gmail, Anda perlu membuat App Password:
1. Buka akun Google → Keamanan
2. Aktifkan Verifikasi 2 Langkah
3. Buka App Passwords → Buat App Password baru
4. Gunakan password yang dihasilkan untuk `MAIL_PASSWORD`

---

## Struktur Database

### Tabel Utama

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna (Admin, Siswa, Orang Tua) |
| `exams` | Data exam/kuesioner |
| `questions` | Bank soal RASEC |
| `exam_question` | Relasi exam dan soal |
| `exam_results` | Hasil tes siswa |
| `connections` | Relasi orang tua dan siswa |
| `password_resets` | Reset password |
| `personal_access_tokens` | API tokens |

### Roles Pengguna

| Role | Deskripsi |
|------|-----------|
| `ADMIN` | Akses penuh untuk mengelola sistem |
| `STUDENT` | Siswa yang mengikuti tes |
| `PARENT` | Orang tua yang memantau anak |

---

## Perintah Artisan yang Berguna

```bash
# Clear cache dan config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Jalankan migration
php artisan migrate
php artisan migrate:fresh          # Hapus dan jalankan ulang
php artisan migrate:fresh --seed   # Dengan data seeder

# Buat data seeder baru
php artisan make:seeder NamaTableSeeder

# Jalankan scheduled tasks
php artisan schedule:work

# List semua route
php artisan route:list

# Generate documentation API
php artisan route:api:docs
```

---

## Struktur Project

```
Growpath/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controller logic
│   │   └── Middleware/       # Auth, role check
│   ├── Models/               # Eloquent models
│   └── Services/             # Business logic
├── config/                   # Konfigurasi Laravel
├── database/
│   ├── migrations/           # Schema database
│   └── seeders/             # Data awal
├── public/                   # Front controller
├── resources/
│   ├── js/                  # Vue/React components
│   └── views/               # Blade templates
├── routes/                  # Route definitions
├── storage/                 # File storage
└── tests/                   # Unit tests
```

---

## Troubleshooting

### Error: Class not found

```bash
composer dump-autoload
```

### Error: Permission denied pada storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Database connection failed

Pastikan kredensial database di `.env` sudah benar dan database sudah dibuat.

### Error: Target class does not exist

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

---

## Lisensi

Project ini adalah open-source software yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
