<?php
// dashboard/my-todo.php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// --- PROSES PERPINDAHAN KANBAN ---
if (isset($_POST['move_task'])) {
    $task_id    = (int)$_POST['task_id'];
    $target_dir = $_POST['direction'];
    
    $current_status_query = mysqli_query($conn, "SELECT status FROM tasks WHERE id = $task_id AND user_id = $user_id");
    if (mysqli_num_rows($current_status_query) === 1) {
        $current_status = mysqli_fetch_assoc($current_status_query)['status'];
        
        $new_status = $current_status;
        if ($current_status == 'todo' && $target_dir == 'right') {
            $new_status = 'doing';
        } elseif ($current_status == 'doing' && $target_dir == 'left') {
            $new_status = 'todo';
        } elseif ($current_status == 'doing' && $target_dir == 'right') {
            $new_status = 'done';
        }
        
        mysqli_query($conn, "UPDATE tasks SET status = '$new_status' WHERE id = $task_id AND user_id = $user_id");
    }
    header("Location: my-todo.php");
    exit;
}

// --- AMBIL DATA KANBAN (HARUS & SEDANG DIKERJAKAN) ---
$todo_tasks  = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'todo' ORDER BY created_at DESC");
$doing_tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'doing' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - TodoSpace</title>
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
                <a href="my-todo.php" class="flex items-center space-x-3 px-4 py-3 bg-brand-50 text-brand-600 rounded-xl font-medium transition-all"><span>Daftar Tugas</span></a>
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
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Papan Kerja Tugas</h2>
            </div>
        </header>

        <!-- KANBAN BOARD -->
        <main class="p-4 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 flex-1 items-start">
            
            <!-- KOLOM 1: HARUS DIKERJAKAN -->
            <div class="bg-slate-100/70 p-4 rounded-2xl border border-slate-200/40 space-y-4">
                <div class="flex items-center justify-between px-2 py-1">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Harus Dikerjakan</h3>
                    <span class="bg-white border border-slate-200 text-slate-500 px-2.5 py-0.5 rounded-md text-xs font-bold"><?php echo mysqli_num_rows($todo_tasks); ?></span>
                </div>

                <div class="space-y-3">
                    <?php if (mysqli_num_rows($todo_tasks) > 0): ?>
                        <?php while($task = mysqli_fetch_assoc($todo_tasks)): ?>
                            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm space-y-3">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-slate-400 text-xs mt-1 break-words"><?php echo htmlspecialchars($task['description']); ?></p>
                                </div>
                                <div class="flex justify-end pt-2 border-t border-slate-50">
                                    <form action="" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <input type="hidden" name="direction" value="right">
                                        <button type="submit" name="move_task" class="text-brand-600 hover:text-brand-700 text-xs font-bold tracking-wider uppercase">
                                            Kerjakan →
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center py-8 text-slate-400 text-xs font-medium">Kolom kosong</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- KOLOM 2: SEDANG DIKERJAKAN -->
            <div class="bg-slate-100/70 p-4 rounded-2xl border border-slate-200/40 space-y-4">
                <div class="flex items-center justify-between px-2 py-1">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Sedang Dikerjakan</h3>
                    <span class="bg-white border border-slate-200 text-slate-500 px-2.5 py-0.5 rounded-md text-xs font-bold"><?php echo mysqli_num_rows($doing_tasks); ?></span>
                </div>

                <div class="space-y-3">
                    <?php if (mysqli_num_rows($doing_tasks) > 0): ?>
                        <?php while($task = mysqli_fetch_assoc($doing_tasks)): ?>
                            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm space-y-3">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-slate-400 text-xs mt-1 break-words"><?php echo htmlspecialchars($task['description']); ?></p>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-50">
                                    <form action="" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <input type="hidden" name="direction" value="left">
                                        <button type="submit" name="move_task" class="text-slate-400 hover:text-slate-600 text-xs font-bold tracking-wider uppercase">
                                            ← Kembalikan
                                        </button>
                                    </form>
                                    <form action="" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                        <input type="hidden" name="direction" value="right">
                                        <button type="submit" name="move_task" class="text-emerald-600 hover:text-emerald-700 text-xs font-bold tracking-wider uppercase">
                                            Selesai ✓
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center py-8 text-slate-400 text-xs font-medium">Kolom kosong</p>
                    <?php endif; ?>
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