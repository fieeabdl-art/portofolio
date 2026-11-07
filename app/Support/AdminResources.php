<?php

namespace App\Support;

use App\Models\Achievement;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PortfolioMessage;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Skill;
use App\Models\Statistic;
use App\Models\WebsiteSetting;
use App\Models\WorkProcess;
use Illuminate\Database\Eloquent\Model;

class AdminResources
{
    public static function all(): array
    {
        return [
            'profile' => [
                'label' => 'Profil Saya', 'model' => Profile::class, 'singular' => 'Profil',
                'fields' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'brand' => ['label' => 'Inisial Logo', 'type' => 'text', 'rules' => 'required|string|max:12'],
                    'profession' => ['label' => 'Profesi', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'tagline' => ['label' => 'Label Hero', 'type' => 'text', 'rules' => 'nullable|string|max:180'],
                    'short_bio' => ['label' => 'Deskripsi Singkat', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    'biography' => ['label' => 'Tentang Saya', 'type' => 'textarea', 'rules' => 'nullable|string|max:10000'],
                    'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:190'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text', 'rules' => 'nullable|string|max:40'],
                    'whatsapp' => ['label' => 'WhatsApp (format internasional)', 'type' => 'text', 'rules' => 'nullable|string|max:40'],
                    'location' => ['label' => 'Lokasi', 'type' => 'text', 'rules' => 'nullable|string|max:150'],
                    'instagram_url' => ['label' => 'Instagram URL', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                    'linkedin_url' => ['label' => 'LinkedIn URL', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                    'github_url' => ['label' => 'GitHub URL', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                    'photo_path' => ['label' => 'Foto Profil', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'about_photo_path' => ['label' => 'Foto Tentang Saya', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'cv_path' => ['label' => 'CV (PDF)', 'type' => 'pdf', 'rules' => 'nullable|mimes:pdf|max:10240'],
                ],
                'columns' => ['name' => 'Nama', 'profession' => 'Profesi', 'email' => 'Email'],
                'single' => true,
            ],
            'statistics' => [
                'label' => 'Statistik', 'model' => Statistic::class, 'singular' => 'Statistik',
                'fields' => [
                    'label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:100'],
                    'value' => ['label' => 'Nilai', 'type' => 'number', 'rules' => 'required|integer|min:0|max:999999'],
                    'suffix' => ['label' => 'Akhiran', 'type' => 'text', 'rules' => 'nullable|string|max:12'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                    'active' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                ],
                'columns' => ['label' => 'Label', 'value' => 'Nilai', 'active' => 'Tampil'],
            ],
            'educations' => [
                'label' => 'Pendidikan', 'model' => Education::class, 'singular' => 'Pendidikan',
                'fields' => [
                    'institution' => ['label' => 'Institusi', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'degree' => ['label' => 'Program / Gelar', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000'],
                    'start_date' => ['label' => 'Mulai', 'type' => 'text', 'rules' => 'nullable|string|max:30'],
                    'end_date' => ['label' => 'Selesai', 'type' => 'text', 'rules' => 'nullable|string|max:30'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                ],
                'columns' => ['institution' => 'Institusi', 'degree' => 'Program', 'start_date' => 'Mulai', 'end_date' => 'Selesai'],
            ],
            'experiences' => [
                'label' => 'Pengalaman', 'model' => Experience::class, 'singular' => 'Pengalaman',
                'fields' => [
                    'company' => ['label' => 'Perusahaan', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'position' => ['label' => 'Posisi', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000'],
                    'start_date' => ['label' => 'Mulai', 'type' => 'text', 'rules' => 'nullable|string|max:30'],
                    'end_date' => ['label' => 'Selesai', 'type' => 'text', 'rules' => 'nullable|string|max:30'],
                    'current' => ['label' => 'Masih bekerja di sini', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                    'image_path' => ['label' => 'Gambar pengalaman (opsional)', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                ],
                'columns' => ['position' => 'Posisi', 'company' => 'Perusahaan', 'start_date' => 'Mulai', 'end_date' => 'Selesai'],
            ],
            'achievements' => [
                'label' => 'Prestasi', 'model' => Achievement::class, 'singular' => 'Prestasi',
                'fields' => [
                    'title' => ['label' => 'Nama Prestasi / Pencapaian', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'organization' => ['label' => 'Pemberi / Organisasi', 'type' => 'text', 'rules' => 'nullable|string|max:180'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000'],
                    'achieved_at' => ['label' => 'Tanggal Diraih', 'type' => 'date', 'rules' => 'nullable|date'],
                    'image_path' => ['label' => 'Foto / Sertifikat (opsional)', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                    'featured' => ['label' => 'Tampilkan di website', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                ],
                'columns' => ['title' => 'Prestasi', 'organization' => 'Pemberi', 'achieved_at' => 'Tanggal', 'featured' => 'Tampil'],
            ],
            'skills' => [
                'label' => 'Keahlian', 'model' => Skill::class, 'singular' => 'Keahlian',
                'fields' => [
                    'name' => ['label' => 'Nama Keahlian', 'type' => 'text', 'rules' => 'required|string|max:100'],
                    'category' => ['label' => 'Kategori', 'type' => 'text', 'rules' => 'required|string|max:100'],
                    'level' => ['label' => 'Penguasaan (%)', 'type' => 'number', 'rules' => 'required|integer|min:0|max:100'],
                    'icon_path' => ['label' => 'Ikon / Gambar', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                    'active' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                ],
                'columns' => ['name' => 'Nama', 'category' => 'Kategori', 'level' => 'Penguasaan', 'active' => 'Tampil'],
            ],
            'categories' => [
                'label' => 'Kategori Proyek', 'model' => ProjectCategory::class, 'singular' => 'Kategori',
                'fields' => [
                    'name' => ['label' => 'Nama Kategori', 'type' => 'text', 'rules' => 'required|string|max:100'],
                    'slug' => ['label' => 'Slug', 'type' => 'text', 'rules' => 'nullable|string|max:120|unique:project_categories,slug'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                ],
                'columns' => ['name' => 'Nama', 'slug' => 'Slug', 'sort_order' => 'Urutan'],
            ],
            'projects' => [
                'label' => 'Proyek', 'model' => Project::class, 'singular' => 'Proyek',
                'fields' => [
                    'title' => ['label' => 'Nama Proyek', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'slug' => ['label' => 'Slug', 'type' => 'text', 'rules' => 'nullable|string|max:200|unique:projects,slug'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => 'categories', 'rules' => 'nullable|exists:project_categories,id'],
                    'summary' => ['label' => 'Ringkasan', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:10000'],
                    'technologies' => ['label' => 'Teknologi (pisahkan dengan koma)', 'type' => 'text', 'rules' => 'nullable|string|max:1000'],
                    'project_date' => ['label' => 'Tanggal Proyek', 'type' => 'date', 'rules' => 'nullable|date'],
                    'demo_url' => ['label' => 'Link Demo', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                    'repository_url' => ['label' => 'Link GitHub (opsional)', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
                    'thumbnail_path' => ['label' => 'Thumbnail', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                    'featured' => ['label' => 'Tampilkan di portofolio', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                    'project_gallery' => ['label' => 'Galeri Foto Proyek', 'type' => 'gallery', 'rules' => 'nullable|array|max:12'],
                ],
                'columns' => ['title' => 'Nama Proyek', 'category_id' => 'Kategori', 'project_date' => 'Tanggal', 'featured' => 'Tampil'],
            ],
            'processes' => [
                'label' => 'Proses Kerja', 'model' => WorkProcess::class, 'singular' => 'Tahap Proses',
                'fields' => [
                    'title' => ['label' => 'Judul Tahapan', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
                    'icon_path' => ['label' => 'Ikon / Gambar', 'type' => 'image', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'],
                    'sort_order' => ['label' => 'Urutan', 'type' => 'number', 'rules' => 'required|integer|min:0'],
                    'active' => ['label' => 'Tampilkan', 'type' => 'checkbox', 'rules' => 'sometimes|boolean'],
                ],
                'columns' => ['title' => 'Tahapan', 'description' => 'Deskripsi', 'active' => 'Tampil'],
            ],
            'messages' => [
                'label' => 'Pesan Masuk', 'model' => PortfolioMessage::class, 'singular' => 'Pesan',
                'fields' => [
                    'name' => ['label' => 'Nama Pengirim', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'email' => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:190'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text', 'rules' => 'nullable|string|max:40'],
                    'subject' => ['label' => 'Subjek', 'type' => 'text', 'rules' => 'required|string|max:180'],
                    'message' => ['label' => 'Isi Pesan', 'type' => 'textarea', 'rules' => 'required|string|max:10000'],
                ],
                'columns' => ['name' => 'Pengirim', 'email' => 'Email', 'subject' => 'Subjek', 'created_at' => 'Diterima'],
                'readonly' => true,
            ],
            'settings' => [
                'label' => 'Pengaturan Website', 'model' => WebsiteSetting::class, 'singular' => 'Pengaturan',
                'fields' => [
                    'key' => ['label' => 'Kunci', 'type' => 'text', 'rules' => 'required|string|max:120|unique:website_settings,key'],
                    'value' => ['label' => 'Nilai', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000'],
                    'group' => ['label' => 'Grup', 'type' => 'text', 'rules' => 'required|string|max:100'],
                    'type' => ['label' => 'Tipe', 'type' => 'select', 'options' => ['text' => 'Teks', 'textarea' => 'Teks panjang', 'url' => 'URL'], 'rules' => 'required|in:text,textarea,url'],
                ],
                'columns' => ['key' => 'Kunci', 'value' => 'Nilai', 'group' => 'Grup', 'type' => 'Tipe'],
            ],
        ];
    }

    public static function get(string $key): array
    {
        abort_unless(isset(self::all()[$key]), 404);

        return self::all()[$key];
    }

    /** @param class-string<Model> $model */
    public static function model(string $model): Model
    {
        abort_unless(in_array($model, array_column(self::all(), 'model'), true), 404);

        return new $model;
    }
}
