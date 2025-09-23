<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dragon Eight Taekwondo Club</title>
    <link rel="stylesheet" href="{{asset("bootstrap/css/bootstrap.css")}}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet">
    
    <!-- Scripts dan Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary">
                <img src="{{ asset('images/logo.png') }}" class="" alt="Responsive image" style="width: 100px";>

            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#berita">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#atlet">Atlet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#artikel">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary text-white ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Dragon Eight Taekwondo Club</h1>
                    <p class="lead text-muted mb-4">
                        Latihan taekwondo dengan sistem pembelajaran yang tepat sasaran. Bergabunglah dengan klub taekwondo terbaik dan raih prestasi gemilang bersama atlet-atlet berbakat lainnya.
                    </p>

                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="https://images.unsplash.com/photo-1601850877056-f8c713aa0833?w=500&h=400&fit=crop&crop=center"
                                     
                             alt="Taekwondo Athletes" class="img-fluid rounded shadow-lg">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Section -->
    <section id="berita" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Berita</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=200&fit=crop&crop=center" 
                             class="card-img-top news-image" alt="Taekwondo Dan Slot Tournament">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">Taekwondo Dan Slot Tournament X</h6>
                            <p class="card-text text-muted small">Turnamen bergengsi yang diikuti oleh atlet-atlet terbaik dari berbagai daerah...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=200&fit=crop&crop=center" 
                             class="card-img-top news-image" alt="Taekwondo Dan Slot Tournament">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">Taekwondo Dan Slot Tournament X</h6>
                            <p class="card-text text-muted small">Turnamen bergengsi yang diikuti oleh atlet-atlet terbaik dari berbagai daerah...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=200&fit=crop&crop=center" 
                             class="card-img-top news-image" alt="Taekwondo Dan Slot Tournament">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">Taekwondo Dan Slot Festival X</h6>
                            <p class="card-text text-muted small">Festival taekwondo dengan berbagai kategori dan tingkat keahlian...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Atlet Berprestasi Section -->
    <section id="atlet" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Atlet Berprestasi</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="athlete-card text-center">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face" 
                                     alt="ALEX" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-8 text-md-start">
                                <h4 class="fw-bold">ALEX</h4>
                                <p class="text-muted mb-3">
                                    Atlet taekwondo berprestasi yang telah meraih berbagai penghargaan tingkat nasional dan internasional. 
                                    Dengan dedikasi dan latihan yang konsisten, Alex menjadi inspirasi bagi atlet muda lainnya.
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-primary">Juara 1 Nasional</span>
                                    <span class="badge bg-success">Dan 5</span>
                                    <span class="badge bg-warning text-dark">Pelatih Bersertifikat</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Artikel Section -->
    <section id="artikel" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Artikel</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=150&h=120&fit=crop&crop=center" 
                                     class="article-image w-100 rounded-start" alt="Nutrisi">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-3">
                                    <h6 class="card-title fw-semibold small">Nutrisi</h6>
                                    <p class="card-text small text-muted">
                                        Panduan nutrisi yang tepat untuk atlet taekwondo, menunjang performa optimal...
                                    </p>
                                    <small class="text-primary">Selengkapnya →</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=150&h=120&fit=crop&crop=center" 
                                     class="article-image w-100 rounded-start" alt="Latihan">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-3">
                                    <h6 class="card-title fw-semibold small">Latihan</h6>
                                    <p class="card-text small text-muted">
                                        Teknik-teknik latihan dasar hingga advanced untuk meningkatkan kemampuan...
                                    </p>
                                    <small class="text-primary">Selengkapnya →</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kata Mereka Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Kata Mereka</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b332c623?w=80&h=80&fit=crop&crop=face" 
                                 alt="User" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                        <p class="small text-muted mb-3">"Saya merasa senang dengan lingkungan latihan yang positif"</p>
                        <div class="text-warning mb-2">★★★★★</div>
                        <strong class="small">Sarah</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face" 
                                 alt="User" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                        <p class="small text-muted mb-3">"Pelatih yang berpengalaman dan metode yang efektif"</p>
                        <div class="text-warning mb-2">★★★★★</div>
                        <strong class="small">Ahmad</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&h=80&fit=crop&crop=face" 
                                 alt="User" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                        <p class="small text-muted mb-3">"Fasilitas lengkap dan suasana yang mendukung"</p>
                        <div class="text-warning mb-2">★★★★★</div>
                        <strong class="small">Maya</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Dragon Eight Club Indonesia</h5>
                    <p class="text-muted">
                        Klub taekwondo terpercaya dengan program latihan berkualitas dan prestasi gemilang.
                    </p>
                </div>

                <div class="col-lg-4 mb-4">
                    <h6 class="fw-semibold mb-3">Kontak</h6>
                    <p class="text-muted mb-2">📍 Jl. Soekarno-Hatta, Batununggal Indah IX No.2 Mengger, Kec. Bandung Kidul Bandung Jawa Barat 40266</p>
                    <p class="text-muted mb-2">📞 (021) 123-4567</p>
                    <p class="text-muted mb-2">✉️ info@dragoneight.com</p>
                    <p class="text-muted">🕐 Sen-Sab: 08:00 - 20:00</p>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">© 2025 Dragon Eight Taekwondo Club. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>