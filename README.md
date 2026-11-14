<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



# SIMHASA — Sistem Informasi Monitoring Hama & Penyakit Tanaman Kelapa Sawit Berbasis Web

**SIMHASA** adalah aplikasi web yang dirancang untuk membantu pencatatan dan pengelolaan data kebun kelapa sawit, referensi hama, referensi penyakit, serta riwayat monitoring kondisi lapangan secara terstruktur[cite: 16, 19].

Proyek ini disusun untuk memenuhi tugas mata kuliah **Bengkel Pemrograman Framework** pada **Program Studi Teknik Informatika, Politeknik Caltex Riau (2026)**[cite: 16].

---

## Tim Penyusun

* **Silvia Sari** (25553012235)
* **Amanda Bilqis Candra Winata** (2555301213)

**Dosen Pengampu:** Erzi Hidayat, S.T., M.Kom 
**Asisten Instruktur Laboran:** Hidayatul Mukmim, S.Tr.Kom

---

## Fitur Utama

* **Autentikasi Pengguna:** Sistem Login, Registrasi akun baru, dan Logout dengan profil pengguna dinamis.
* **Dashboard Interaktif:** Ringkasan jumlah data kebun, hama, penyakit, catatan monitoring, grafik donat statistik kondisi lapangan (Chart.js), serta daftar riwayat monitoring terbaru.
* **Master Data Kebun (CRUD):** Pengelolaan nama kebun, pemilik, lokasi, dan luas lahan.
* **Master Data Hama (CRUD):** Inventarisasi nama hama, gejala serangan, dan cara penanganan berdasarkan referensi pertanian.
* **Master Data Penyakit (CRUD):** Inventarisasi nama penyakit, gejala umum, dan langkah penanganan.
* **Aktivitas Monitoring (CRUD):** Pencatatan tanggal pemeriksaan, kebun yang diperiksa, jenis gangguan (Hama / Penyakit / Sehat), nama gangguan, dan keterangan kondisi lapangan.
* **Pencarian Real-Time:** Fitur penyaringan data instan pada setiap tabel kelola data.

> **Catatan Ruang Lingkup:** Informasi gejala dan penanganan pada sistem ini bersifat referensi awal untuk pencatatan dan tidak menggantikan diagnosis langsung dari tenaga ahli perkebunan

---

## Teknologi yang Digunakan

* **Framework Backend:** Laravel (PHP 8.3)
* **Frontend:** HTML5, CSS3, Bootstrap 5, Bootstrap Icons, Chart.js
* **Database:** MySQL / MariaDB

---

## Cara Instalasi & Menjalankan Proyek

1. **Clone repositori ini:**
   ```bash
   git clone <url-repositori-github-kamu>
   cd simhasa