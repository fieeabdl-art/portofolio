# Portofolio Glass Silver

Website portofolio personal berbasis Laravel 13 dengan tampilan Glass Silver yang diadaptasi dari `Portofolio Glass Silver.html`, Blade, Vite, dan CMS admin. Data portofolio disimpan di database; foto pribadi tidak disediakan secara palsu dan dapat diunggah lewat CMS.

## Kebutuhan

- PHP 8.3 atau lebih baru (PHP 8.5 telah digunakan untuk pengembangan ini)
- Composer 2
- Node.js 20+ dan NPM
- MySQL 8+ (SQLite juga dapat digunakan untuk development)
- Ekstensi PHP Laravel, termasuk PDO MySQL

## Instalasi lokal

1. Salin `.env.example` menjadi `.env`.
2. Buat database MySQL kosong bernama `portofolio` atau sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.
3. Isi `ADMIN_EMAIL` dan `ADMIN_PASSWORD` dengan kredensial awal yang unik sebelum menjalankan seeder.
4. Pastikan server MySQL hidup dan database `portofolio` sudah dibuat, lalu jalankan:

   ```sh
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   npm install
   npm run build
   php artisan serve
   ```

5. Buka `http://127.0.0.1:8000`; halaman CMS ada di `/admin/login`.

Konfigurasi `.env` lokal dan `.env.example` menggunakan MySQL. SQLite hanya opsi alternatif untuk pengujian atau development tanpa server database: set `DB_CONNECTION=sqlite`, siapkan file `database/database.sqlite`, lalu jalankan migrasi dan seeder yang sama.

## Akun admin awal

Nilai contoh `.env.example`:

- Email: `admin@example.com`
- Kata sandi: `ChangeMeToAUniquePassword123!`

Ganti kedua nilai tersebut di `.env` **sebelum** `php artisan migrate --seed`. Seeder tidak mengganti password akun yang sudah ada saat dijalankan kembali. Setelah masuk, buka **Pengaturan Akun** untuk mengganti nama/email atau membuat password baru (minimal 12 karakter dan memerlukan password saat ini). Jika password terlanjur terekspos, ubah segera dari halaman ini.

## CMS

CMS tersedia setelah autentikasi dan menyediakan:

- Dashboard dan daftar pesan kontak, lengkap dengan status baca, balas email, dan hapus.
- Profil, statistik, pendidikan, pengalaman, prestasi, keahlian, kategori proyek, proyek, galeri gambar proyek, tahapan kerja, pengaturan website, dan pengaturan akun.
- Galeri media untuk unggah dan melihat file; media yang masih direferensikan konten ditandai dan tidak dapat dihapus. Ganti/hapus rujukan konten dahulu untuk menghapus file tersebut.
- Upload foto profil, foto tentang saya, gambar opsional untuk pengalaman kerja, ikon keahlian/proses, thumbnail dan galeri proyek, CV PDF, serta pratinjau pilihan media di form.
- Jenis/ukuran file divalidasi di server. Gambar dibatasi 5 MB; PDF CV hingga 10 MB.

Konten awal yang muncul setelah seeding merupakan placeholder yang dapat diperbarui atau dihapus lewat CMS. Kolom foto profil sengaja kosong; sebelum foto diunggah, halaman menampilkan orb Glass Silver sebagai placeholder, bukan wajah sintetis.

## Pemeriksaan

```sh
php artisan test
php artisan route:list
npm run build
```

Migrasi hanya menambahkan tabel dan kolom aplikasi. Perintah instalasi tidak menggunakan `migrate:fresh` atau menghapus database.
