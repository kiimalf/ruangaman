# 🛡️ RuangAman

**Sistem Pakar Klasifikasi Hukum & Generator Dokumen BAP untuk Penyintas Kekerasan Seksual (UU TPKS)**

[![SDGs 5](https://img.shields.io/badge/SDGs-5%20Kesetaraan%20Gender-E5243B?style=flat-square)](https://sdgs.un.org/goals/goal5)
[![SDGs 16](https://img.shields.io/badge/SDGs-16%20Perdamaian%20%26%20Keadilan-00689D?style=flat-square)](https://sdgs.un.org/goals/goal16)

---

## 📋 Deskripsi

RuangAman adalah platform web **anonim** yang membantu penyintas kekerasan seksual untuk:

1. **Memvalidasi** apakah insiden yang dialami memenuhi unsur pelanggaran UU TPKS melalui sistem pakar *backward chaining*
2. **Menghasilkan** draf dokumen kronologis (BAP) secara otomatis untuk mempermudah pelaporan ke Satgas PPKS

### Prinsip Utama
- 🔒 **100% Anonim** — Tidak ada registrasi, login, atau penyimpanan data pribadi
- 🚪 **Quick Exit** — Tombol keluar darurat di setiap halaman (redirect ke Google.com)
- ⚖️ **Akurasi Hukum** — Semua aturan hukum dari database, divalidasi oleh pakar
- 📄 **PDF Ephemeral** — Dokumen BAP di-generate on-demand, tidak disimpan di server

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Frontend | React 18 + Vite |
| Styling | Tailwind CSS v3 |
| Routing | React Router DOM v6 |
| State Management | Zustand v4 |
| Backend | Laravel 11 |
| Database | MySQL 8.0+ |
| Admin Panel | Laravel Filament v3 |
| PDF Generator | Laravel DomPDF |
| Auth (Admin) | Laravel Sanctum |

---

## 🚀 Instalasi Lokal

### Prasyarat
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x
- MySQL 8.0+
- Git

### 1. Clone Repository

```bash
git clone https://github.com/your-team/ruangaman.git
cd ruangaman
```

### 2. Setup Backend (Laravel)

```bash
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ruangaman
# DB_USERNAME=root
# DB_PASSWORD=

# Jalankan migration
php artisan migrate

# Jalankan seeder (knowledge base)
php artisan db:seed

# Buat akun admin Filament
php artisan make:filament-user

# Jalankan server
php artisan serve
```

Backend berjalan di: `http://localhost:8000`

### 3. Setup Frontend (React)

```bash
cd frontend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Jalankan dev server
npm run dev
```

Frontend berjalan di: `http://localhost:5173`

---

## 📂 Struktur Proyek

```
ruangaman/
├── backend/                  # Laravel 11 API & Admin
│   ├── app/
│   │   ├── Models/           # Eloquent models
│   │   ├── Services/         # InferenceEngine (backward chaining)
│   │   └── Filament/         # Admin panel resources
│   ├── database/
│   │   ├── migrations/       # Database schema
│   │   └── seeders/          # Knowledge base seed data
│   └── resources/
│       └── views/pdf/        # Template BAP (DomPDF)
│
├── frontend/                 # React 18 + Vite SPA
│   └── src/
│       ├── pages/            # Landing, Session, Conclusion
│       ├── components/       # UI components
│       └── store/            # Zustand state management
│
└── README.md
```

---

## 🔑 Akses Admin Panel

Setelah membuat akun admin:

1. Buka `http://localhost:8000/admin`
2. Login dengan email & password yang dibuat
3. Kelola knowledge base: Rules, Questions, Hypotheses

---

## 📡 API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/session/start` | Mulai sesi baru (anonim) |
| POST | `/api/session/answer` | Kirim jawaban pertanyaan |
| GET | `/api/session/conclude` | Dapatkan hasil klasifikasi |
| GET | `/api/session/export-pdf` | Download draf BAP (PDF) |

---

## 🎯 Milestone Pengembangan

| Milestone | Status | Deliverable |
|-----------|--------|-------------|
| M0 — Fondasi & Infrastruktur | ✅ | Repo, Laravel, DB schema, env config |
| M1 — Knowledge Base | ✅ | Seed data rules UU TPKS (10 hipotesis, 17 pertanyaan, 13 rules) |
| M2 — Mesin Backward Chaining | ✅ | InferenceEngine service, API endpoints, 14 tests passed |
| M3 — Frontend Core | ✅ | React SPA: landing, pertanyaan, konklusi |
| M4 — PDF Generator | ✅ | DomPDF BAP template, /export-pdf endpoint, Frontend integrated |
| M5 — Admin Panel & Polish | ✅ | Filament StatsOverview, SEO noindex, CSP Middleware |
| M6 — QA & Deployment | ⬜ | Bug fix, deploy, UAT |

---

## ⚠️ Disclaimer

> Hasil klasifikasi dari RuangAman **bukan keputusan hukum final**. Platform ini bertujuan memberikan pemahaman awal tentang UU TPKS. Konsultasikan dengan Lembaga Bantuan Hukum (LBH) atau Satgas PPKS untuk penanganan lebih lanjut.