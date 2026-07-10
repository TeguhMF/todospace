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
                Platform manajemen tugas personal yang dirancang khusus untuk membantu Anda tetap fokus, terorganisir, dan konsisten mencapai target harian.
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
        </div>
    </section>

    <!-- PROGRAM SECTION -->
    <section id="program" class="py-20 max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-brand-600 text-sm font-bold tracking-wider uppercase">Alur Kerja</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2 tracking-tight">
                Metode Manajemen Tugas di TodoSpace
            </h2>
            <p class="text-slate-500 mt-3">Tiga pilar utama yang membentuk ekosistem produktivitas Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Program 1 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600 font-bold text-lg mb-6 border border-brand-100">01</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Kumpulkan Ide</h3>
                <p class="text-slate-500 leading-relaxed text-sm">
                    Tulis semua tugas, ide, atau tanggung jawab yang melintas di pikiran Anda ke dalam Inbox terlebih dahulu tanpa perlu memikirkan kategori.
                </p>
            </div>
            <!-- Program 2 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600 font-bold text-lg mb-6 border border-brand-100">02</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Strukturisasi & Jadwalkan</h3>
                <p class="text-slate-500 leading-relaxed text-sm">
                    Pindahkan tugas ke My Todo atau tautkan ke Project Progress tertentu. Tentukan tenggat waktu yang jelas pada sistem kalender terintegrasi.
                </p>
            </div>
            <!-- Program 3 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600 font-bold text-lg mb-6 border border-brand-100">03</div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Evaluasi Statistik</h3>
                <p class="text-slate-500 leading-relaxed text-sm">
                    Pantau grafik performa penyelesaian tugas Anda melalui halaman riwayat untuk mengukur tingkat produktivitas mingguan.
                </p>
            </div>
        </div>
    </section>

    <!-- SERVICE SECTION -->
    <section id="service" class="py-20 bg-slate-900 text-white rounded-t-[2.5rem]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="text-brand-500 text-sm font-bold tracking-wider uppercase">Layanan Utama</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 tracking-tight">Layanan Fitur Ruang Kerja Anda</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="border border-slate-800 p-6 rounded-xl bg-slate-800/30">
                    <h4 class="font-bold text-lg mb-2 text-brand-400">Multi-Status Task</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Pemisahan tugas yang jelas antara My Task, My Todo, Inbox, dan History.</p>
                </div>
                <div class="border border-slate-800 p-6 rounded-xl bg-slate-800/30">
                    <h4 class="font-bold text-lg mb-2 text-brand-400">Project Progress Tracker</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Pantau persentase penyelesaian proyek besar Anda secara real-time.</p>
                </div>
                <div class="border border-slate-800 p-6 rounded-xl bg-slate-800/30">
                    <h4 class="font-bold text-lg mb-2 text-brand-400">Calendar Integration</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Visualisasi tenggat waktu tugas dalam format kalender bulanan yang bersih.</p>
                </div>
                <div class="border border-slate-800 p-6 rounded-xl bg-slate-800/30">
                    <h4 class="font-bold text-lg mb-2 text-brand-400">Data Analytics</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Metrik dan statistik sederhana untuk meninjau efisiensi waktu kerja Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-500 text-sm py-8 border-t border-slate-800 text-center">
        <p>&copy; 2026 TodoSpace. TMF PRODUCTION.</p>
    </footer>

</body>
</html>