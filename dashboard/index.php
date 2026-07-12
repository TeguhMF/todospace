<?php
// dashboard/index.php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// --- PROSES TAMBAH TUGAS (INSERT DENGAN DEADLINE) ---
if (isset($_POST['add_task'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);
    $deadline    = !empty($_POST['deadline']) ? "'" . mysqli_real_escape_string($conn, $_POST['deadline']) . "'" : "NULL";
    
    $insert_query = "INSERT INTO tasks (user_id, project_id, title, description, status, deadline, created_at) 
                     VALUES ($user_id, NULL, '$title', '$description', '$status', $deadline, NOW())";
    
    if (mysqli_query($conn, $insert_query)) {
        header("Location: index.php");
        exit;
    }
}

// --- MENGAMBIL DATA STATISTIK ---
$count_inbox = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = $user_id AND status = 'inbox'"))['total'];
$count_todo  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = $user_id AND status = 'todo'"))['total'];
$count_doing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = $user_id AND status = 'doing'"))['total'];
$count_done  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = $user_id AND status = 'done'"))['total'];

// --- AMBIL DAFTAR TUGAS AKTIF TERURUT PRIORITAS AI & DEADLINE ---
$tasks_list_query = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id AND status != 'done' ORDER BY FIELD(ai_priority, 'Tinggi', 'Sedang', 'Rendah') DESC, deadline ASC");

// --- LOGIKA KALENDER INTERAKTIF TANPA BATAS ---
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if ($bulan > 12) { $bulan = 1; $tahun++; }
if ($bulan < 1) { $bulan = 12; $tahun--; }

$nama_bulan_array = [
    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 
    5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 
    9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
];
$nama_bulan = $nama_bulan_array[$bulan] . " " . $tahun;

$hari_pertama = date('w', strtotime("$tahun-$bulan-01"));
$total_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

$prev_bulan = $bulan - 1; $prev_tahun = $tahun;
$next_bulan = $bulan + 1; $next_tahun = $tahun;
if ($prev_bulan < 1) { $prev_bulan = 12; $prev_tahun--; }
if ($next_bulan > 12) { $next_bulan = 1; $next_tahun++; }

// Query untuk penanda kalender
$tugas_deadline = [];
$start_date = "$tahun-" . str_pad($bulan, 2, "0", STR_PAD_LEFT) . "-01";
$end_date   = "$tahun-" . str_pad($bulan, 2, "0", STR_PAD_LEFT) . "-" . str_pad($total_hari, 2, "0", STR_PAD_LEFT);

$deadline_query = mysqli_query($conn, "SELECT title, description, status, DAY(deadline) as hari_deadline FROM tasks WHERE user_id = $user_id AND status != 'done' AND deadline BETWEEN '$start_date' AND '$end_date'");
while ($row = mysqli_fetch_assoc($deadline_query)) {
    $tugas_deadline[$row['hari_deadline']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor - TodoSpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <button onclick="toggleSidebar(false)" class="md:hidden text-slate-400 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <nav class="p-4 space-y-1">
    <a href="index.php" class="flex items-center space-x-3 px-4 py-3 bg-brand-50 text-brand-600 rounded-xl font-medium transition-all"><span>Beranda</span></a>
    <a href="my-task.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Semua Tugas</span></a>
    <a href="my-todo.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Daftar Tugas</span></a>
    <a href="inbox.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Kotak Masuk</span></a>
    <a href="history.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Riwayat</span></a>
    
    <!-- REVISI POSISI: DI BAWAH RIWAYAT DAN TANPA SVG -->
    <a href="group-randomizer.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Pembagi Kelompok</span></a>
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
            <a href="../auth/logout.php" class="text-slate-400 hover:text-red-500 p-1 rounded transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg></a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <div class="md:pl-64 min-h-screen flex flex-col">
        <header class="h-20 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar(true)" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Dashboard</h2>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- REVISI: ICON SVG PREMIUM UNTUK TOMBOL AI -->
                <a href="analyze-ai.php" class="bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold shadow-sm flex items-center space-x-2 transition-all transform hover:scale-[1.02]">
                    <svg class="w-4 h-4 text-white animate-pulse" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5L11 9L15 11L11 13L9 17L7 13L3 11L7 9L9 5Z" fill="currentColor"/>
                        <path d="M19 13L20 15L22 16L20 17L19 19L18 17L16 16L18 15L19 13Z" fill="currentColor"/>
                        <path d="M19 3L19.75 4.5L21.25 5.25L19.75 6L19 7.5L18.25 6L16.75 5.25L18.25 4.5L19 3Z" fill="currentColor"/>
                    </svg>
                    <span>Analisis Prioritas AI</span>
                </a>
                <button onclick="toggleModal(true)" class="bg-brand-600 hover:bg-brand-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-medium shadow-sm transition-all">+ Tugas</button>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-6 sm:space-y-8 flex-1">
            <!-- BANNER NOTIFIKASI -->
            <?php if(isset($_GET['status']) && $_GET['status'] == 'aisynced'): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm rounded-2xl font-semibold flex items-center space-x-2.5 shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Sukses! Algoritma AI berhasil menganalisis teks instruksi kuliah dan menyusun ulang prioritas tugas Anda secara cerdas.</span>
                </div>
            <?php endif; ?>

            <!-- KARTU STATISTIK -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm"><span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kotak Masuk</span><p class="text-2xl font-bold text-slate-900 mt-1"><?php echo $count_inbox; ?></p></div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm"><span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Harus Dikerjakan</span><p class="text-2xl font-bold text-slate-900 mt-1"><?php echo $count_todo; ?></p></div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm"><span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Dikerjakan</span><p class="text-2xl font-bold text-slate-900 mt-1"><?php echo $count_doing; ?></p></div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm"><span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai</span><p class="text-2xl font-bold text-brand-600 mt-1"><?php echo $count_done; ?></p></div>
            </section>

            <!-- ANALITIK, DAFTAR PRIORITAS AI, & KALENDER -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-start">
                
                <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                    <!-- GRAFIK ANALITIK DOUGHNUT -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm h-[320px] flex flex-col justify-between">
                        <div><h3 class="text-base font-bold text-slate-900">Analitik Aktivitas</h3></div>
                        <div class="relative w-full h-[150px] flex items-center justify-center my-auto"><canvas id="modernDoughnutChart"></canvas></div>
                        <div class="grid grid-cols-4 gap-2 text-[11px] font-semibold pt-4 border-t border-slate-100">
                            <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-slate-300 block"></span><span class="text-slate-500">Masuk: <?php echo $count_inbox; ?></span></div>
                            <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-amber-400 block"></span><span class="text-slate-500">Harus: <?php echo $count_todo; ?></span></div>
                            <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 block"></span><span class="text-slate-500">Sedang: <?php echo $count_doing; ?></span></div>
                            <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 block"></span><span class="text-slate-500">Selesai: <?php echo $count_done; ?></span></div>
                        </div>
                    </div>

                    <!-- DAFTAR KARTU TUGAS -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-900">Urutan Prioritas Kerja</h3>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider bg-slate-50 border border-slate-200/60 px-2.5 py-1 rounded-lg">Terurut Pintar</span>
                        </div>
                        <div class="space-y-4">
                            <?php if(mysqli_num_rows($tasks_list_query) == 0): ?>
                                <p class="text-slate-400 text-xs font-medium text-center py-8">Belum ada tugas aktif untuk dianalisis oleh AI.</p>
                            <?php else: ?>
                                <?php while($task = mysqli_fetch_assoc($tasks_list_query)): ?>
                                    <div class="p-4 bg-white border border-slate-200 rounded-2xl space-y-2.5 shadow-sm hover:border-slate-300 transition-all">
                                        <div class="flex justify-between items-start space-x-4">
                                            <div>
                                                <h4 class="font-bold text-slate-900 text-sm leading-snug"><?php echo htmlspecialchars($task['title']); ?></h4>
                                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Tenggat: <span class="text-slate-600 font-semibold"><?php echo $task['deadline'] ?? 'Tidak ada'; ?></span></p>
                                            </div>
                                            <?php 
                                                $badgeStyle = "bg-slate-100 text-slate-500 border border-slate-200";
                                                if($task['ai_priority'] == 'Tinggi') $badgeStyle = "bg-red-50 text-red-700 border border-red-200/80";
                                                if($task['ai_priority'] == 'Sedang') $badgeStyle = "bg-amber-50 text-amber-700 border border-amber-200/80";
                                                if($task['ai_priority'] == 'Rendah') $badgeStyle = "bg-emerald-50 text-emerald-700 border border-emerald-200/80";
                                            ?>
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-lg whitespace-nowrap <?php echo $badgeStyle; ?>">
                                                AI: <?php echo $task['ai_priority'] ?? 'Belum Diurutkan'; ?>
                                            </span>
                                        </div>
                                        <p class="text-slate-600 text-xs leading-relaxed whitespace-pre-wrap"><?php echo htmlspecialchars($task['description']); ?></p>
                                        
                                        <!-- REVISI: INSIGHT BOX DENGAN ICON SVG CAPAIAN / KILAT MODERN -->
                                        <?php if(!empty($task['ai_insight'])): ?>
                                            <div class="p-3 bg-indigo-50/50 border border-indigo-100/70 rounded-xl flex items-start space-x-2.5 mt-2">
                                                <div class="mt-0.5 text-indigo-600 flex-shrink-0">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9 21H15M12 14C9.79086 14 8 12.2091 8 10C8 7.79086 9.79086 6 12 6C14.2091 6 16 7.79086 16 10C16 12.2091 14.2091 14 12 14ZM12 2v2M4.22 4.22l1.42 1.42M2 12h2M18.36 5.64l1.42-1.42M20 12h2M12 18v1" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <p class="text-[11px] text-indigo-950 font-medium italic leading-relaxed">
                                                    <strong>Saran AI:</strong> <?php echo htmlspecialchars($task['ai_insight']); ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- KALENDER INTERAKTIF -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-base font-bold text-slate-900">Kalender Kerja</h3>
                        <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-xl">
                            <a href="?bulan=<?php echo $prev_bulan; ?>&tahun=<?php echo $prev_tahun; ?>" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 font-bold">&larr;</a>
                            <span class="text-xs font-bold text-slate-700 px-2"><?php echo $nama_bulan; ?></span>
                            <a href="?bulan=<?php echo $next_bulan; ?>&tahun=<?php echo $next_tahun; ?>" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 font-bold">&rarr;</a>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="grid grid-cols-7 gap-y-2 text-center text-[11px] font-bold text-slate-400 mb-2">
                            <div class="text-red-500">Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                        </div>
                        <div class="grid grid-cols-7 gap-y-1.5 text-xs font-semibold text-slate-700">
                            <?php
                            for ($i = 0; $i < $hari_pertama; $i++) { echo '<div class="py-2 text-slate-200/60 select-none">•</div>'; }
                            for ($hari = 1; $hari <= $total_hari; $hari++) {
                                // Penanda tanggal hari ini dinamis (12 Juli 2026)
                                $isToday = ($hari == 12 && $bulan == 7 && $tahun == 2026) ? true : false;
                                $hasDeadline = isset($tugas_deadline[$hari]);
                                
                                $bgClass = "hover:bg-slate-100 text-slate-700";
                                $onClickAction = "";

                                if ($hasDeadline) {
                                    $bgClass = "bg-amber-100 text-amber-800 border border-amber-300 font-bold scale-105";
                                    $json_data = htmlspecialchars(json_encode($tugas_deadline[$hari]), ENT_QUOTES, 'UTF-8');
                                    $onClickAction = "onclick='showDeadlineDetail($hari, `$json_data`)'";
                                }

                                if ($isToday) {
                                    $bgClass = "bg-brand-600 text-white font-bold shadow-md shadow-brand-600/20";
                                }

                                echo '<div class="flex items-center justify-center py-0.5">';
                                echo '<button type="button" ' . $onClickAction . ' class="w-8 h-8 flex items-center justify-center rounded-full transition-all text-xs focus:outline-none ' . $bgClass . '">' . $hari . '</button>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </section>
        </main>
    </div>

    <!-- MODAL 1: TAMBAH TUGAS BARU -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-md rounded-2xl border border-slate-200 shadow-xl overflow-hidden p-6 transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-slate-900">Buat Tugas Baru</h3>
                <button onclick="toggleModal(false)" class="text-slate-400 text-sm font-medium">Batal</button>
            </div>
            <form action="" method="POST" class="space-y-4">
                <input type="text" name="title" placeholder="Judul Tugas Kuliah" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-brand-500 focus:bg-white transition-all">
                <textarea name="description" placeholder="Info tugas (Contoh: [Matkul: Rekayasa Perangkat Lunak] Buat mockup jersey dan dokumen SRS...)" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-brand-500 focus:bg-white transition-all resize-none"></textarea>
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1 ml-1">Tenggat Waktu (Deadline)</label>
                    <input type="date" name="deadline" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-brand-500 focus:bg-white transition-all text-slate-600">
                </div>
                <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium focus:outline-none focus:border-brand-500 focus:bg-white transition-all">
                    <option value="todo">Masukkan ke Harus Dikerjakan</option>
                    <option value="doing">Masukkan ke Sedang Dikerjakan</option>
                    <option value="inbox">Masukkan ke Kotak Masuk</option>
                </select>
                <button type="submit" name="add_task" class="w-full bg-brand-600 hover:bg-brand-700 text-white py-3 rounded-xl font-semibold text-xs uppercase tracking-wider transition-all shadow-md">Simpan Tugas</button>
            </form>
        </div>
    </div>

    <!-- MODAL 2: DETAIL TANGGAL DEADLINE -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-md rounded-2xl border border-slate-200 shadow-xl overflow-hidden p-6 transform scale-95 transition-transform duration-300">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-base font-bold text-slate-900">Agenda Tugas Tanggal <span id="modalTargetDate" class="text-brand-600"></span></h3>
                <button onclick="toggleDetailModal(false)" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-wider transition-colors">Tutup</button>
            </div>
            <div id="modalTasksContainer" class="space-y-3 max-h-[260px] overflow-y-auto pr-1"></div>
        </div>
    </div>

    <script>
        function toggleSidebar(show) {
            const s = document.getElementById('sidebar'); const o = document.getElementById('sidebarOverlay');
            if (show) { s.classList.remove('-translate-x-full'); o.classList.remove('hidden'); }
            else { s.classList.add('-translate-x-full'); o.classList.add('hidden'); }
        }
        function toggleModal(show) {
            const m = document.getElementById('taskModal'); const c = m.querySelector('.transform');
            if (show) { m.classList.remove('hidden'); setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); c.classList.add('scale-100'); }, 20); }
            else { m.classList.add('opacity-0'); c.classList.remove('scale-100'); c.classList.add('scale-95'); setTimeout(() => { m.classList.add('hidden'); }, 300); }
        }
        function toggleDetailModal(show) {
            const m = document.getElementById('detailModal'); const c = m.querySelector('.transform');
            if (show) { m.classList.remove('hidden'); setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); c.classList.add('scale-100'); }, 20); }
            else { m.classList.add('opacity-0'); c.classList.remove('scale-100'); c.classList.add('scale-95'); setTimeout(() => { m.classList.add('hidden'); }, 300); }
        }
        function showDeadlineDetail(hari, jsonString) {
            document.getElementById('modalTargetDate').innerText = hari + ' <?php echo $nama_bulan_array[$bulan]; ?>';
            const container = document.getElementById('modalTasksContainer'); container.innerHTML = '';
            const listTugas = JSON.parse(jsonString);
            listTugas.forEach(tugas => {
                const item = document.createElement('div');
                item.className = "p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5";
                let bc = "bg-slate-200 text-slate-700";
                if(tugas.status === 'todo') bc = "bg-amber-100 text-amber-700";
                if(tugas.status === 'doing') bc = "bg-blue-100 text-blue-700";
                item.innerHTML = `<div class="flex justify-between items-start space-x-2">
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug">${tugas.title}</h4>
                    <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded ${bc}">${tugas.status}</span>
                </div><p class="text-slate-500 text-xs leading-relaxed">${tugas.description}</p>`;
                container.appendChild(item);
            });
            toggleDetailModal(true);
        }

        // --- CHART DOUGHNUT ---
        const ctx = document.getElementById('modernDoughnutChart').getContext('2d');
        const cInbox = <?php echo $count_inbox; ?>, cTodo = <?php echo $count_todo; ?>, cDoing = <?php echo $count_doing; ?>, cDone = <?php echo $count_done; ?>;
        const isEmpty = (cInbox + cTodo + cDoing + cDone) === 0;
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Kotak Masuk', 'Harus Dikerjakan', 'Sedang Dikerjakan', 'Selesai'],
                datasets: [{
                    data: isEmpty ? [1, 0, 0, 0] : [cInbox, cTodo, cDoing, cDone],
                    backgroundColor: isEmpty ? ['#e2e8f0', '#fbbf24', '#3b82f6', '#10b981'] : ['#cbd5e1', '#fbbf24', '#3b82f6', '#10b981'],
                    borderWidth: 4, borderColor: '#ffffff', borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '82%', plugins: { legend: { display: false }, tooltip: { enabled: !isEmpty } } }
        });
    </script>
</body>
</html>