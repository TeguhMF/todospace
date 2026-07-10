<?php
// dashboard/my-task.php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// --- PROSES UPDATE STATUS TUGAS ---
if (isset($_POST['update_status'])) {
    $task_id    = (int)$_POST['task_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE tasks SET status = '$new_status' WHERE id = $task_id AND user_id = $user_id";
    mysqli_query($conn, $update_query);
    header("Location: my-task.php");
    exit;
}

// --- PROSES HAPUS TUGAS ---
if (isset($_POST['delete_task'])) {
    $task_id = (int)$_POST['task_id'];
    
    $delete_query = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";
    mysqli_query($conn, $delete_query);
    header("Location: my-task.php");
    exit;
}

// --- AMBIL DAFTAR TUGAS ---
$tasks_query = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Tugas - TodoSpace</title>
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
                <a href="my-task.php" class="flex items-center space-x-3 px-4 py-3 bg-brand-50 text-brand-600 rounded-xl font-medium transition-all"><span>Semua Tugas</span></a>
                <a href="my-todo.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Daftar Tugas</span></a>
                <a href="inbox.php" class="flex items-center space-x-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl font-medium transition-all"><span>Kotak Masuk</span></a>
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
            <a href="../auth/logout.php" class="text-slate-400 hover:text-red-500 p-1 rounded transition-colors" title="Keluar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <div class="md:pl-64 min-h-screen flex flex-col">
        <header class="h-20 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar(true)" class="md:hidden text-slate-600 hover:text-slate-900 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Daftar Semua Tugas</h2>
            </div>
        </header>

        <main class="p-4 sm:p-8 flex-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-4 px-6">Tugas</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm font-medium">
                            <?php if (mysqli_num_rows($tasks_query) > 0): ?>
                                <?php while($task = mysqli_fetch_assoc($tasks_query)): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-slate-900"><?php echo htmlspecialchars($task['title']); ?></p>
                                            <p class="text-slate-400 text-xs mt-1 max-w-md break-words"><?php echo htmlspecialchars($task['description']); ?></p>
                                        </td>
                                        <td class="py-4 px-6">
                                            <form action="" method="POST" class="inline-block">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <select name="status" onchange="this.form.submit()" 
                                                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold focus:outline-none bg-white cursor-pointer transition-all">
                                                    <option value="inbox" <?php echo ($task['status'] == 'inbox') ? 'selected' : ''; ?>>Kotak Masuk</option>
                                                    <option value="todo" <?php echo ($task['status'] == 'todo') ? 'selected' : ''; ?>>Harus Dikerjakan</option>
                                                    <option value="doing" <?php echo ($task['status'] == 'doing') ? 'selected' : ''; ?>>Sedang Dikerjakan</option>
                                                    <option value="done" <?php echo ($task['status'] == 'done') ? 'selected' : ''; ?>>Selesai</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                        </td>
                                        <td class="py-4 px-6">
                                            <form action="" method="POST" onsubmit="return confirm('Hapus tugas ini?')" class="inline-block">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <button type="submit" name="delete_task" class="text-red-500 hover:text-red-700 font-bold text-xs transition-colors">
                                                    HAPUS
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-slate-400 text-xs sm:text-sm">
                                        Belum ada tugas terdaftar. Tambahkan tugas dari beranda dasbor.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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