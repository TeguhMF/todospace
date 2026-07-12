<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoSpace - Tingkatkan Produktivitas Pribadi Anda</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi custom warna putih-biru modern untuk Tailwind
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- NAVIGATION BAR -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md border-b border-slate-100 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="#home" class="text-2xl font-bold tracking-tight text-brand-600">
                Todo<span class="text-slate-900">Space</span>
            </a>

            <!-- Menu Links -->
            <div class="hidden md:flex items-center space-x-8 font-medium text-slate-600">
                <a href="#home" class="hover:text-brand-600 transition-colors">Home</a>
                <a href="#about" class="hover:text-brand-600 transition-colors">About</a>
                <a href="#program" class="hover:text-brand-600 transition-colors">Program</a>
                <a href="#service" class="hover:text-brand-600 transition-colors">Service</a>
            </div>

            <!-- Auth Button -->
            <div>
                <a href="auth/login.php" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-lg font-medium transition-all shadow-sm shadow-brand-500/10">
                    Masuk
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION (HOME) -->
    <section id="home" class="relative pt-32 pb-20 md:pt-44 md:pb-32 max-w-7xl mx-auto px-6 flex flex-col items-center text-center overflow-hidden h-screen bg-slate-50" 
             style="background-image: 
                linear-gradient(to right, rgba(59, 130, 246, 0.15) 1px, transparent 1px), 
                linear-gradient(to bottom, rgba(59, 130, 246, 0.15) 1px, transparent 1px); 
                background-size: 50px 50px;">
        
        <!-- Efek sorotan cahaya radial di tengah (Cyberpunk Glow) -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_20%,#f8fafc_80%)] pointer-events-none"></div>

        <div class="relative z-10 flex flex-col items-center">
            <span class="bg-brand-50 text-brand-700 text-sm font-semibold px-4 py-1.5 rounded-full mb-6 border border-brand-100 shadow-inner">
                Ruang Kerja Digital Pribadi
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 max-w-4xl leading-tight">
                Kelola Tugas Harian Tanpa Distraksi di <span class="text-brand-600">TodoSpace</span>
            </h1>
            <p class="mt-6 text-lg text-slate-500 max-w-2xl leading-relaxed">
                Platform manajemen tugas personal yang dirancang khusus untuk membantu Anda tetap focus, terorganisir, dan konsisten mencapai target harian.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="auth/login.php" class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-4 rounded-xl font-medium transition-all shadow-lg shadow-brand-600/20 text-center hover:-translate-y-0.5">
                    Mulai Sekarang
                </a>
                <a href="#about" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-medium transition-all text-center hover:border-brand-300">
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="py-20 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <!-- Sisi Kiri: Teks Penjelasan -->
            <div>
                <span class="text-brand-600 text-sm font-bold tracking-wider uppercase">Tentang Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2 tracking-tight">
                    Sistem Manajemen Tugas yang Minimalis dan Efektif
                </h2>
                <p class="mt-4 text-slate-500 leading-relaxed">
                    TodoSpace hadir untuk menyelesaikan masalah tumpukan tugas yang tidak teratur. Dikembangkan khusus untuk penggunaan pribadi, platform ini mengutamakan kecepatan akses, kejelasan visual, dan struktur data yang intuitif.
                </p>
                <p class="mt-4 text-slate-500 leading-relaxed">
                    Dengan antarmuka yang bersih, Anda dapat memetakan fokus harian tanpa terganggu oleh fitur-fitur kompleks yang tidak Anda butuhkan.
                </p>
            </div>
                     
            <!-- Frame Foto -->
            <div class="relative bg-slate-100 rounded-2xl overflow-hidden border border-slate-200 shadow-md">
                <img src="assets/about image.png" 
                     alt="TodoSpace Workspace" 
                     class="w-full h-72 md:h-96 object-cover object-center grayscale-[20%] hover:grayscale-0 transition-all duration-500 ease-in-out transform hover:scale-102">
            </div>
        </div>
    </section>

    <!-- MODIFIKASI: PROGRAM SECTION DENGAN INTEGRASI SPLIT GRID & IMAGE PREVIEW -->
    <section id="program" class="py-20 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-5 gap-12 items-center">
            
            <!-- Blok Kiri (Lebar 3 Kolom): Berisi Judul & Tiga Pilar Alur Kerja -->
            <div class="lg:col-span-3 space-y-10">
                <div>
                    <span class="text-brand-600 text-sm font-bold tracking-wider uppercase">Alur Kerja</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2 tracking-tight">
                        Metode Manajemen Tugas di TodoSpace
                    </h2>
                    <p class="text-slate-500 mt-2 text-sm sm:text-base">Tiga pilar utama yang membentuk ekosistem produktivitas Anda.</p>
                </div>

                <div class="space-y-6">
                    <!-- Program 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-start space-x-4">
                        <div class="w-12 h-12 bg-brand-50 text-brand-600 font-bold text-lg flex items-center justify-center rounded-xl flex-shrink-0 border border-brand-100">01</div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Kumpulkan Ide & Tugas</h3>
                            <p class="text-slate-500 leading-relaxed text-sm">
                                Tulis semua tugas, ide, atau tanggung jawab perkuliahan Anda ke dalam Inbox terlebih dahulu tanpa perlu memikirkan tingkat kesulitan materi.
                            </p>
                        </div>
                    </div>
                    <!-- Program 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-start space-x-4">
                        <div class="w-12 h-12 bg-brand-50 text-brand-600 font-bold text-lg flex items-center justify-center rounded-xl flex-shrink-0 border border-brand-100">02</div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Strukturisasi & Jadwalkan</h3>
                            <p class="text-slate-500 leading-relaxed text-sm">
                                Pindahkan tugas ke My Todo atau pantau sisa pengerjaan secara berkala. Tentukan tenggat waktu yang jelas pada sistem kalender terintegrasi.
                            </p>
                        </div>
                    </div>
                    <!-- Program 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-start space-x-4">
                        <div class="w-12 h-12 bg-brand-50 text-brand-600 font-bold text-lg flex items-center justify-center rounded-xl flex-shrink-0 border border-brand-100">03</div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Evaluasi Analitik Statistik</h3>
                            <p class="text-slate-500 leading-relaxed text-sm">
                                Pantau grafik performa penyelesaian tugas Anda melalui halaman dasbor analitik untuk mengukur tingkat konsistensi belajar mingguan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blok Kanan (Lebar 2 Kolom): Berisi Visual Gambar Screenshot Dasbor -->
            <div class="lg:col-span-2">
                <div class="bg-white p-3 border border-slate-200 shadow-xl rounded-2xl transform lg:rotate-2 hover:rotate-0 transition-all duration-300">
                    <img src="assets/preview.png" 
                         alt="Pratinjau Aplikasi TodoSpace" 
                         class="w-full h-auto bg-slate-50 rounded-xl border border-slate-100 object-cover min-h-[300px] lg:min-h-[420px]">
                </div>
            </div>

        </div>
    </section>

    <!-- MODIFIKASI: SERVICE SECTION (KARTU ELEGAN LAYANAN INTEGRASI TERBARU) -->
    <section id="service" class="py-20 bg-slate-900 text-white rounded-t-[2.5rem]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="text-brand-500 text-sm font-bold tracking-wider uppercase">Layanan Utama</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 tracking-tight">Layanan Fitur Ruang Kerja Anda</h2>
            </div>

            <!-- Menggunakan Grid 3 Kolom yang Seimbang, Rapi, dan Berisi Modul Utama Terbaru -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Kartu Layanan 1 -->
                <div class="border border-slate-800 p-6 rounded-2xl bg-slate-800/30 space-y-2">
                    <h4 class="font-bold text-lg text-brand-400">Manajemen Prioritas AI</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Pengurutan skala prioritas tugas otomatis menggunakan kecerdasan buatan berdasarkan tenggat waktu dan kompleksitas deskripsi materi kuliah Anda.
                    </p>
                </div>
                <!-- Kartu Layanan 2 -->
                <div class="border border-slate-800 p-6 rounded-2xl bg-slate-800/30 space-y-2">
                    <h4 class="font-bold text-lg text-brand-400">Kalender Agenda Interaktif</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Visualisasi deadline tugas yang belum selesai langsung ke dalam format kalender bulanan terpadu untuk monitoring linimasa yang efisien.
                    </p>
                </div>
                <!-- Kartu Layanan 3 -->
                <div class="border border-slate-800 p-6 rounded-2xl bg-slate-800/30 space-y-2">
                    <h4 class="font-bold text-lg text-brand-400">Pembagi Kelompok Otomatis</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Modul pembagian anggota tim acak secara adil untuk kebutuhan kerja kelompok angkatan, lengkap dengan fitur salin format pesan WhatsApp kelas.
                    </p>
                </div>
            </div>
        </div>
    </section>

<!-- FOOTER (REVISI: Latar belakang melebar penuh, konten tetap presisi di tengah) -->
    <footer class="w-full bg-slate-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between text-slate-500 text-sm gap-2">
            <p>&copy; 2026 TodoSpace. Hak Cipta Dilindungi.</p>
            <p class="text-xs">TMF PRODUCTION</p>
        </div>
    </footer>
    <script>
        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>

</body>
</html>