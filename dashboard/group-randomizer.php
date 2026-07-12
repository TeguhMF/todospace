<?php
// dashboard/group-randomizer.php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembagi Kelompok - TodoSpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen">

    <div id="sidebarOverlay" onclick="toggleSidebar(false)" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-20 hidden md:hidden"></div>

    <!-- SIDEBAR NAVIGASI -->
    <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 fixed inset-y-0 left-0 z-30 flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div>
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="index.php" class="text-xl font-bold tracking-tight text-brand-600">
                    Todo<span class="text-slate-900">Space</span>
                </a>
                <button onclick="toggleSidebar(false)" class="md:hidden text-slate-400 hover:text-slate-600">Tutup</button>
            </div>
            <nav class="p-4 space-y-1">
                <a href="index.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl font-medium transition-all"><span>Beranda</span></a>
                <a href="my-task.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl font-medium transition-all"><span>Semua Tugas</span></a>
                <a href="my-todo.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl font-medium transition-all"><span>Daftar Tugas</span></a>
                <a href="inbox.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl font-medium transition-all"><span>Kotak Masuk</span></a>
                <a href="history.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-xl font-medium transition-all"><span>Riwayat</span></a>
                <a href="group-randomizer.php" class="flex items-center space-x-3 px-4 py-3 bg-brand-50 text-brand-600 rounded-xl font-medium transition-all"><span>Pembagi Kelompok</span></a>
            </nav>
        </div>
        <div class="p-4 border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-brand-600 rounded-lg flex items-center justify-center text-white font-semibold uppercase">
                    <?php echo substr($username, 0, 2); ?>
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-slate-900 leading-tight"><?php echo htmlspecialchars($username); ?></p>
                    <p class="text-xs text-slate-400">Akun Pribadi</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <div class="md:pl-64 min-h-screen flex flex-col">
        <header class="h-20 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar(true)" class="md:hidden text-slate-600 font-medium p-1">Menu</button>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Pembagi Kelompok</h2>
            </div>
            <div>
                <a href="index.php" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all">
                    Kembali
                </a>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-6 sm:space-y-8 flex-1">
            
            <!-- PANEL MANAJEMEN INPUT PEMBAGI KELOMPOK -->
            <section class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
                <div class="pb-4 mb-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Kelompok Kerja</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Sistem pembagian kelompok.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 ml-1">Daftar Nama Anggota</label>
                        <textarea id="namesInput" rows="7" placeholder="Tempel daftar nama mahasiswa di sini..." 
                                  class="w-full p-4 border border-slate-200 bg-slate-50 text-slate-800 text-sm font-medium focus:outline-none focus:border-brand-500 focus:bg-white rounded-2xl resize-none transition-all"></textarea>
                    </div>

                    <div class="flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 ml-1">Target Jumlah Kelompok</label>
                            <input type="number" id="groupCountInput" min="2" value="2" 
                                   class="w-full p-3 border border-slate-200 bg-slate-50 font-semibold text-sm focus:outline-none focus:border-brand-500 focus:bg-white rounded-2xl transition-all">
                        </div>

                        <div class="pt-2 space-y-2.5">
                            <button type="button" onclick="generateGroups()" 
                                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm py-3.5 rounded-2xl shadow-sm hover:shadow transition-all transform hover:scale-[1.01]">
                                Acak Anggota Kelompok
                            </button>
                            <button type="button" onclick="clearInput()" 
                                    class="w-full text-slate-400 hover:text-slate-600 font-semibold text-xs py-2 transition-colors">
                                Bersihkan Kolom Input
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- AREA MATRIKS OUTPUT HASIL PENGACAKAN -->
            <section id="resultSection" class="space-y-4 hidden">
                <div class="flex flex-row items-center justify-between bg-white border border-slate-200 p-4 rounded-2xl shadow-sm">
                    <p class="text-xs sm:text-sm font-bold text-slate-900">Hasil Distribusi Tim</p>
                    <button type="button" onclick="copyToClipboard()" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                        Salin Pesan Konten
                    </button>
                </div>

                <div id="groupsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Cards termuat otomatis di sini -->
                </div>
            </section>

        </main>
    </div>

    <script>
        let formattedTextResult = "";

        function toggleSidebar(show) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (show) { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
            else { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }
        }

        function clearInput() {
            document.getElementById('namesInput').value = '';
            document.getElementById('resultSection').classList.add('hidden');
        }

        function generateGroups() {
            const rawText = document.getElementById('namesInput').value;
            const groupCount = parseInt(document.getElementById('groupCountInput').value);

            const names = rawText.split('\n')
                                 .map(name => name.trim())
                                 .filter(name => name.length > 0);

            if (names.length === 0) {
                alert("Harap masukkan daftar nama mahasiswa terlebih dahulu!");
                return;
            }

            if (groupCount < 2 || groupCount > names.length) {
                alert("Jumlah kelompok tidak valid!");
                return;
            }

            // Algoritma Pengacakan Fisher-Yates
            for (let i = names.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [names[i], names[j]] = [names[j], names[i]];
            }

            const groups = Array.from({ length: groupCount }, () => []);

            names.forEach((name, index) => {
                const groupIndex = index % groupCount;
                groups[groupIndex].push(name);
            });

            const container = document.getElementById('groupsContainer');
            container.innerHTML = '';
            formattedTextResult = "*HASIL PEMBAGIAN KELOMPOK TUGAS KULIAH* \n\n";

            groups.forEach((group, index) => {
                const groupNum = index + 1;
                const paddedNum = groupNum.toString().padStart(2, '0');

                formattedTextResult += `*KELOMPOK ${paddedNum}*:\n`;

                const card = document.createElement('div');
                card.className = "bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:border-slate-300 transition-all";
                
                let membersListHtml = "";
                group.forEach((member, mIndex) => {
                    const mNum = mIndex + 1;
                    membersListHtml += `
                        <li class="flex items-center space-x-3 py-2.5 border-b border-slate-50 last:border-0 font-medium text-xs sm:text-sm text-slate-700">
                            <span class="w-5 h-5 bg-slate-100 text-slate-500 font-mono text-[10px] flex items-center justify-center rounded-lg font-bold">${mNum}</span>
                            <span>${member}</span>
                        </li>`;
                    formattedTextResult += `${mNum}. ${member}\n`;
                });
                
                formattedTextResult += "\n";

                card.innerHTML = `
                    <div class="p-4 bg-slate-50/70 border-b border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-900 tracking-wide">KELOMPOK ${paddedNum}</span>
                        <span class="bg-white border border-slate-200 text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-lg">${group.length} Anggota</span>
                    </div>
                    <div class="p-4 bg-white">
                        <ul class="divide-y divide-slate-50">
                            ${membersListHtml}
                        </ul>
                    </div>
                `;
                
                container.appendChild(card);
            });

            document.getElementById('resultSection').classList.remove('hidden');
            document.getElementById('resultSection').scrollIntoView({ behavior: 'smooth' });
        }

        function copyToClipboard() {
            if (!formattedTextResult) return;
            navigator.clipboard.writeText(formattedTextResult.trim()).then(() => {
                alert("Hasil pembagian kelompok telah disalin ke clipboard. Silakan tempel (paste) ke grup WhatsApp.");
            }).catch(err => {
                alert("Gagal menyalin teks.");
            });
        }
    </script>
</body>
</html>