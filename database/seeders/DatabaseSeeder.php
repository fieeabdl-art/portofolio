<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Skill;
use App\Models\Statistic;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\WorkProcess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => 'Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Password123!')),
                'is_admin' => true,
            ],
        )->forceFill(['is_admin' => true])->save();

        Profile::firstOrCreate([], [
            'name' => 'Nama Anda',
            'brand' => 'NA.',
            'profession' => 'Web Developer',
            'tagline' => 'Pengembang web untuk bisnis Indonesia',
            'short_bio' => 'Saya membangun aplikasi web yang cepat, rapi, dan mudah dikelola, dari desain sampai rilis.',
            'biography' => 'Perbarui bagian ini dengan cerita, keahlian, dan tujuan profesional Anda melalui CMS.',
            'email' => 'halo@namaanda.com',
            'location' => 'Lokasi Anda',
        ]);

        foreach ([
            ['label' => 'Tahun pengalaman', 'value' => 6],
            ['label' => 'Proyek selesai', 'value' => 40],
            ['label' => 'Klien puas', 'value' => 18],
            ['label' => 'Kota terlayani', 'value' => 12],
        ] as $index => $statistic) {
            Statistic::firstOrCreate(['label' => $statistic['label']], $statistic + ['sort_order' => $index]);
        }

        foreach ([
            ['name' => 'Web', 'slug' => 'web', 'sort_order' => 1],
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'sort_order' => 2],
        ] as $category) {
            ProjectCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }

        $webCategory = ProjectCategory::where('slug', 'web')->firstOrFail();
        $dashboardCategory = ProjectCategory::where('slug', 'dashboard')->firstOrFail();
        foreach ([
            ['title' => 'Dashboard Keuangan', 'slug' => 'dashboard-keuangan', 'summary' => 'Aplikasi web · Laporan real-time', 'category_id' => $dashboardCategory->id, 'technologies' => 'Laravel, MySQL'],
            ['title' => 'Company Profile', 'slug' => 'company-profile', 'summary' => 'Situs bisnis · CMS admin', 'category_id' => $webCategory->id, 'technologies' => 'Laravel, Blade'],
            ['title' => 'Katalog Properti', 'slug' => 'katalog-properti', 'summary' => 'Situs dinamis · Pencarian', 'category_id' => $webCategory->id, 'technologies' => 'Laravel, MySQL'],
        ] as $index => $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project + ['sort_order' => $index + 1]);
        }

        foreach (['Laravel', 'React', 'Inertia', 'MySQL', 'Docker', 'Tailwind', 'Vite', 'Git'] as $index => $name) {
            Skill::firstOrCreate(['name' => $name], ['category' => 'Pengembangan Web', 'level' => 80, 'sort_order' => $index + 1, 'active' => true]);
        }

        foreach ([
            ['title' => 'Pahami', 'description' => 'Tujuan dan pengguna.'],
            ['title' => 'Rancang', 'description' => 'Alur dan tampilan.'],
            ['title' => 'Bangun', 'description' => 'Kode bersih dan teruji.'],
            ['title' => 'Uji', 'description' => 'Cek di semua perangkat.'],
            ['title' => 'Rilis', 'description' => 'Deploy dan serah terima.'],
        ] as $index => $process) {
            WorkProcess::firstOrCreate(['title' => $process['title']], $process + ['sort_order' => $index + 1, 'active' => true]);
        }

        foreach ([
            ['key' => 'contact_heading', 'value' => 'Mari buat sesuatu yang luar biasa', 'group' => 'Kontak'],
            ['key' => 'contact_intro', 'value' => 'Terbuka untuk proyek freelance dan kolaborasi.', 'group' => 'Kontak'],
            ['key' => 'footer_note', 'value' => 'Dibuat dengan teliti dan sentuhan Glass Silver.', 'group' => 'Footer'],
        ] as $setting) {
            WebsiteSetting::firstOrCreate(['key' => $setting['key']], $setting + ['type' => 'text']);
        }
    }
}
