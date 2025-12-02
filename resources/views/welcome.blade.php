<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILOG - Sistem Logistik Polres</title>
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary: #dc2626;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
        }
        
        body {
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo img {
            height: 40px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .cta-button {
            background-color: var(--primary);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .cta-button:hover {
            background-color: var(--primary-light);
        }
        
        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            padding: 8rem 0 5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-primary {
            background-color: white;
            color: var(--primary);
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: white;
            color: var(--primary);
        }
        
        /* Features Section */
        .features {
            padding: 5rem 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .section-title p {
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background-color: var(--light);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            background-color: var(--primary-light);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .feature-card h3 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .feature-card p {
            color: var(--gray);
        }
        
        /* How It Works */
        .how-it-works {
            padding: 5rem 0;
            background-color: var(--light);
        }
        
        .steps {
            display: flex;
            justify-content: space-between;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }
        
        .steps::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 10%;
            right: 10%;
            height: 3px;
            background-color: var(--primary-light);
            z-index: 1;
        }
        
        .step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
            padding: 0 1rem;
        }
        
        .step-number {
            background-color: var(--primary);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .step h3 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .step p {
            color: var(--gray);
        }
        
        /* Testimonials */
        .testimonials {
            padding: 5rem 0;
            background-color: white;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .testimonial-card {
            background-color: var(--light);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            color: var(--gray);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .author-info h4 {
            color: var(--dark);
        }
        
        .author-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            max-width: 600px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .steps {
                flex-direction: column;
                gap: 2rem;
            }
            
            .steps::before {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-text">SILOG</div>
                </div>
                <ul class="nav-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <a href="/login" class="cta-button">Masuk</a>
                <div class="mobile-menu">☰</div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Sistem Manajemen Logistik Polres</h1>
            <p>Solusi terintegrasi untuk mengelola inventaris, distribusi, dan pemeliharaan aset logistik Polres secara efisien dan transparan.</p>
            <div class="hero-buttons">
                <a href="/register" class="btn-primary">Daftar Sekarang</a>
                <a href="#features" class="btn-secondary">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Fitur Unggulan</h2>
                <p>Manajemen logistik yang komprehensif untuk mendukung operasional Polres</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3>Manajemen Inventaris</h3>
                    <p>Pantau dan kelola stok barang dengan sistem pencatatan yang akurat dan real-time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3>Distribusi Barang</h3>
                    <p>Kelola proses distribusi logistik ke berbagai unit dengan sistem pelacakan yang terintegrasi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Pemeliharaan Aset</h3>
                    <p>Jadwalkan dan lacak pemeliharaan rutin untuk semua aset logistik Polres.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Laporan & Analitik</h3>
                    <p>Buat laporan komprehensif dan analisis data untuk pengambilan keputusan yang lebih baik.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Keamanan Data</h3>
                    <p>Sistem keamanan berlapis untuk melindungi data sensitif logistik Polres.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Akses Mobile</h3>
                    <p>Akses sistem dari perangkat mobile untuk kemudahan operasional di lapangan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-title">
                <h2>Cara Kerja Sistem</h2>
                <p>Proses sederhana untuk mengelola logistik Polres secara efektif</p>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Registrasi & Login</h3>
                    <p>Daftarkan unit Anda dan akses sistem dengan kredensial yang aman.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Input Data Logistik</h3>
                    <p>Masukkan data inventaris, permintaan, dan distribusi barang.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Kelola Proses</h3>
                    <p>Pantau dan kelola seluruh proses logistik dari satu platform.</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Analisis & Laporan</h3>
                    <p>Buat laporan dan analisis untuk evaluasi dan perencanaan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Apa Kata Pengguna</h2>
                <p>Testimoni dari berbagai Polres yang telah menggunakan sistem kami</p>
            </div>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"Sistem ini sangat membantu dalam mengelola logistik kami. Proses distribusi menjadi lebih efisien dan transparan."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">AK</div>
                        <div class="author-info">
                            <h4>Aiptu Budi Santoso</h4>
                            <p>Polres Jakarta Selatan</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Dengan sistem ini, kami dapat memantau stok barang secara real-time dan mengurangi kesalahan dalam pencatatan."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">IP</div>
                        <div class="author-info">
                            <h4>Ipda Sari Dewi</h4>
                            <p>Polres Bandung</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"Laporan yang dihasilkan sangat membantu dalam evaluasi dan perencanaan anggaran logistik tahunan kami."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">KO</div>
                        <div class="author-info">
                            <h4>Kompol Andi Wijaya</h4>
                            <p>Polres Surabaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Siap Mengoptimalkan Manajemen Logistik Polres Anda?</h2>
            <p>Daftar sekarang dan rasakan kemudahan mengelola logistik dengan sistem terintegrasi kami.</p>
            <a href="/register" class="btn-primary">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>SILOG</h3>
                    <p>Sistem Manajemen Logistik Polres yang terintegrasi dan efisien.</p>
                </div>
                <div class="footer-column">
                    <h3>Tautan Cepat</h3>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#how-it-works">Cara Kerja</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Kontak</h3>
                    <ul class="footer-links">
                        <li>Email: info@silog-polres.id</li>
                        <li>Telepon: (021) 1234-5678</li>
                        <li>Alamat: Jl. Sudirman No. 123, Jakarta</li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Keamanan</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 SILOG - Sistem Logistik Polres. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu').addEventListener('click', function() {
            document.querySelector('.nav-links').style.display = 
                document.querySelector('.nav-links').style.display === 'flex' ? 'none' : 'flex';
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if(window.innerWidth <= 768) {
                        document.querySelector('.nav-links').style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>