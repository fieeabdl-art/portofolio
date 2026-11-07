<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="{{ $profile->short_bio }}">
    <title>{{ $profile->name }} · {{ $profile->profession }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<nav class="site-nav" aria-label="Navigasi utama">
    <div class="glass">
        <a class="logo chrome" href="#atas">{{ $profile->brand }}</a>
        <ul>
            <li><a href="#karya">Karya</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#keahlian">Keahlian</a></li>
            <li><a href="#proses">Proses</a></li>
            <li><a href="#kontak">Kontak</a></li>
        </ul>
        <a class="btn" href="#kontak">Ajak ngobrol</a>
    </div>
</nav>

<main class="wrap" id="atas">
    <section class="hero">
        <div>
            <small class="in" style="--i:0">{{ $profile->tagline }}</small>
            <h1 class="chrome in" style="--i:1">{!! nl2br(e($profile->profession)) !!}</h1>
            <p class="in" style="--i:2">{{ $profile->short_bio }}</p>
            <div class="row in" style="--i:3">
                <a class="btn" href="#karya">Lihat karya</a>
                @if($profile->cv_path)
                    <a class="btn ghost" href="{{ route('cv.download') }}">Unduh CV</a>
                @endif
            </div>
        </div>
        <div class="orb in" style="--i:2" role="img" aria-label="{{ $profile->photo_path ? 'Foto profil '.$profile->name : 'Placeholder foto profil' }}">
            @if($profile->photo_path)
                <img src="{{ asset('storage/'.$profile->photo_path) }}" alt="Foto {{ $profile->name }}">
            @endif
        </div>
    </section>

    @if($statistics->isNotEmpty())
        <section class="glass stats in" style="--i:4" aria-label="Statistik">
            @foreach($statistics as $statistic)
                <div><b class="chrome" data-n="{{ $statistic->value }}" data-suffix="{{ $statistic->suffix }}"></b><span>{{ $statistic->label }}</span></div>
            @endforeach
        </section>
    @endif

    <h2 class="section-title" id="karya">Karya pilihan
        @if($projects->isNotEmpty())
            <span class="tabs" role="group" aria-label="Filter karya">
                <button aria-pressed="true" data-filter="all">Semua</button>
                @foreach($categories as $category)
                    <button aria-pressed="false" data-filter="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
            </span>
        @endif
    </h2>
    @if($projects->isNotEmpty())
        <div class="work" id="work">
            @foreach($projects as $index => $project)
                <article class="glass card project-card" data-category="{{ $project->category?->slug ?? '' }}">
                    <div class="art a{{ ($index % 3) + 1 }}">
                        @if($project->thumbnail_path)
                            <img src="{{ asset('storage/'.$project->thumbnail_path) }}" alt="{{ $project->title }}">
                        @endif
                    </div>
                    <h3>{{ $project->title }}</h3>
                    <p>{{ $project->summary ?: $project->category?->name }}@if($project->project_date) · {{ $project->project_date->format('Y') }}@endif</p>
                    @if($project->technologies)
                        <p>{{ $project->technologies }}</p>
                    @endif
                    @if($project->description)
                        <details class="project-description"><summary>Lihat detail proyek</summary><p>{{ $project->description }}</p></details>
                    @endif
                    @if($project->images->isNotEmpty())
                        <div class="project-gallery" aria-label="Galeri {{ $project->title }}">
                            @foreach($project->images as $image)
                                <a href="{{ asset('storage/'.$image->path) }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->alt ?? $project->title }}">
                                </a>
                            @endforeach
                        </div>
                    @endif
                    @if($project->demo_url || $project->repository_url)
                        <div class="project-links">
                            @if($project->demo_url)<a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer">Demo</a>@endif
                            @if($project->repository_url)<a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer">GitHub</a>@endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @else
        <div class="glass empty">Proyek akan ditampilkan di sini setelah ditambahkan melalui CMS.</div>
    @endif

    @if($profile->biography || $educations->isNotEmpty() || $experiences->isNotEmpty() || $achievements->isNotEmpty())
        <h2 class="section-title" id="tentang">Tentang saya</h2>
        <section class="about-grid">
            @if($profile->biography)
                <article class="glass about-panel">
                    @if($profile->about_photo_path || $profile->photo_path)
                        <img class="about-photo" src="{{ asset('storage/'.($profile->about_photo_path ?: $profile->photo_path)) }}" alt="Foto {{ $profile->name }}">
                    @endif
                    <h3>{{ $profile->name }}</h3>
                    <p>{!! nl2br(e($profile->biography)) !!}</p>
                </article>
            @endif
            @if($educations->isNotEmpty() || $experiences->isNotEmpty() || $achievements->isNotEmpty())
                <article class="glass about-panel">
                    @foreach($experiences as $experience)
                        <div class="timeline-item">
                            @if($experience->image_path)
                                <img class="experience-photo" src="{{ asset('storage/'.$experience->image_path) }}" alt="Gambar pengalaman {{ $experience->position }} di {{ $experience->company }}">
                            @endif
                            <b>{{ $experience->position }} · {{ $experience->company }}</b>
                            <small>{{ $experience->start_date }} — {{ $experience->current ? 'Sekarang' : $experience->end_date }}</small>
                            @if($experience->description)<p>{{ $experience->description }}</p>@endif
                        </div>
                    @endforeach
                    @foreach($educations as $education)
                        <div class="timeline-item">
                            <b>{{ $education->degree }} · {{ $education->institution }}</b>
                            <small>{{ $education->start_date }} — {{ $education->end_date }}</small>
                            @if($education->description)<p>{{ $education->description }}</p>@endif
                        </div>
                    @endforeach
                    @foreach($achievements as $achievement)
                        <div class="timeline-item achievement-item">
                            @if($achievement->image_path)
                                <img class="achievement-photo" src="{{ asset('storage/'.$achievement->image_path) }}" alt="Sertifikat atau bukti {{ $achievement->title }}">
                            @endif
                            <b>{{ $achievement->title }}</b>
                            <small>{{ $achievement->organization }}@if($achievement->achieved_at) · {{ $achievement->achieved_at->format('Y') }}@endif</small>
                            @if($achievement->description)<p>{{ $achievement->description }}</p>@endif
                        </div>
                    @endforeach
                </article>
            @endif
        </section>
    @endif

    @if($skills->isNotEmpty())
        <div class="two" id="keahlian" style="margin-top:56px">
            <section class="glass caps" aria-label="Kategori keahlian">
                @foreach($skills->groupBy('category') as $category => $items)
                    <div><h4>{{ $category }}</h4><p>{{ $items->pluck('name')->join(' · ') }}</p></div>
                @endforeach
            </section>
            <section class="glass tools" aria-label="Keahlian">
                @foreach($skills as $skill)
                    <span title="Penguasaan {{ $skill->level }}%">
                        @if($skill->icon_path)<img src="{{ asset('storage/'.$skill->icon_path) }}" alt="" width="18" height="18" style="vertical-align:middle;object-fit:contain">@endif
                        {{ $skill->name }}
                    </span>
                @endforeach
            </section>
        </div>
    @endif

    @if($processes->isNotEmpty())
        <h2 class="section-title" id="proses">Cara kerja saya</h2>
        <section class="glass flow">
            @foreach($processes as $process)
                <div>
                    @if($process->icon_path)<img src="{{ asset('storage/'.$process->icon_path) }}" alt="" width="24" height="24">@endif
                    <b>{{ $process->title }}</b><p>{{ $process->description }}</p>
                </div>
            @endforeach
        </section>
    @endif

    <section class="glass cta" id="kontak">
        <div>
            <h2 class="chrome">{{ $settings['contact_heading'] ?? 'Mari buat sesuatu yang luar biasa' }}</h2>
            <p>{{ $settings['contact_intro'] ?? 'Terbuka untuk proyek freelance dan kolaborasi.' }}</p>
            @if(session('contact_sent'))<div class="flash" role="status">{{ session('contact_sent') }}</div>@endif
            @if($profile->email)<p><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></p>@endif
            @if($profile->location)<p>{{ $profile->location }}</p>@endif
            @if($profile->whatsapp)<p><a class="btn ghost" href="https://wa.me/{{ preg_replace('/\D+/', '', $profile->whatsapp) }}" target="_blank" rel="noopener noreferrer">WhatsApp</a></p>@endif
        </div>
        <form class="contact-form" method="post" action="{{ route('contact.store') }}">
            @csrf
            <div class="form-pair">
                <label><span class="sr-only">Nama</span><input name="name" value="{{ old('name') }}" placeholder="Nama" autocomplete="name" required></label>
                <label><span class="sr-only">Email</span><input type="email" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email" required></label>
            </div>
            <label><span class="sr-only">WhatsApp atau telepon (opsional)</span><input name="phone" value="{{ old('phone') }}" placeholder="WhatsApp / telepon (opsional)" autocomplete="tel"></label>
            <label><span class="sr-only">Subjek</span><input name="subject" value="{{ old('subject') }}" placeholder="Subjek" required></label>
            <label><span class="sr-only">Pesan</span><textarea name="message" placeholder="Ceritakan kebutuhan Anda..." required>{{ old('message') }}</textarea></label>
            @error('name')<small class="form-errors">{{ $message }}</small>@enderror
            @error('email')<small class="form-errors">{{ $message }}</small>@enderror
            @error('subject')<small class="form-errors">{{ $message }}</small>@enderror
            @error('message')<small class="form-errors">{{ $message }}</small>@enderror
            <button class="btn" type="submit">Kirim pesan</button>
        </form>
    </section>
    <footer>
        <span>© {{ now()->year }} {{ $profile->name }} · {{ $settings['footer_note'] ?? '' }}</span>
        <span>
            @if($profile->linkedin_url)<a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>@endif
            @if($profile->github_url)<a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer">GitHub</a>@endif
            @if($profile->instagram_url)<a href="{{ $profile->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram</a>@endif
        </span>
    </footer>
</main>
</body>
</html>
