<?php
// dashboard/inbox.php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// --- PROSES PINDAHKAN TUGAS KE HARUS DIKERJAKAN (TODO) ---
if (isset($_POST['move_to_todo'])) {
    $task_id = (int)$_POST['task_id'];
    mysqli_query($conn, "UPDATE tasks SET status = 'todo' WHERE id = $task_id AND user_id = $user_id");
    header("Location: inbox.php");
    exit;
}

// --- AMBIL DATA KHUSUS INBOX ---
$inbox_tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'inbox' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kotak Masuk - TodoSpace</title>
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

    <!-- NAVIGASI SIDEBAR -->
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
                <a href="index.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Beranda</span></a>
                <a href="my-task.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Semua Tugas</span></a>
                <a href="my-todo.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Daftar Tugas</span></a>
                <a href="inbox.php" class="flex items-center space-x-3 px-4 py-3 bg-brand-50 text-brand-600 rounded-xl font-medium transition-all"><span>Kotak Masuk</span></a>
                <a href="history.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Riwayat</span></a>
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
                <button onclick="toggleSidebar(true)" class="md:hidden text-slate-600 hover:text-slate-900 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Kotak Masuk Tugas Baru</h2>
            </div>
        </header>

        <main class="p-4 sm:p-8 space-y-4 flex-1">
            <?php if (mysqli_num_rows($inbox_tasks) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php while($task = mysqli_fetch_assoc($inbox_tasks)): ?>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-sm flex flex-col justify-between items-start space-y-4">
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base"><?php echo htmlspecialchars($task['title']); ?></h3>
                                <p class="text-slate-400 text-xs sm:text-sm mt-1.5 break-words"><?php echo htmlspecialchars($task['description']); ?></p>
                            </div>
                            <form action="" method="POST" class="w-full pt-2 border-t border-slate-50 flex justify-end">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="move_to_todo" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-all">
                                    Pindahkan ke Daftar Tugas →
                                </button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <p class="text-xs sm:text-sm text-slate-400 font-medium">Kotak masuk kosong. Tidak ada tugas baru yang tertunda.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function toggleSidebar(show) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>