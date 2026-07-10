<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password == $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $error = 'Password yang Anda masukkan salah.';
        }
    } else {
        $error = 'Username tidak terdaftar dalam sistem.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TodoSpace</title>
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex items-center justify-center p-4 md:p-6 relative overflow-hidden"
      style="background-image: 
        linear-gradient(to right, rgba(59, 130, 246, 0.08) 1px, transparent 1px), 
        linear-gradient(to bottom, rgba(59, 130, 246, 0.08) 1px, transparent 1px); 
        background-size: 50px 50px;">

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_20%,#f8fafc_85%)] pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-[850px] bg-white rounded-[32px] border border-slate-200/50 shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[500px]">

        <div class="flex flex-col justify-center items-center px-8 py-12 sm:px-12 lg:px-16 w-full">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Sign in</h2>
            
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-6">atau gunakan akun admin</p>

   
            <?php if (!empty($error)): ?>
                <div class="w-full mb-4 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs font-medium text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="w-full space-y-4">
                <div>
                    <input type="text" id="username" name="username" placeholder="Username" required autocomplete="off"
                           class="w-full px-5 py-3.5 rounded-xl border border-transparent bg-slate-100 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100 transition-all text-sm font-medium">
                </div>

                <div>
                    <input type="password" id="password" name="password" placeholder="Password" required
                           class="w-full px-5 py-3.5 rounded-xl border border-transparent bg-slate-100 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100 transition-all text-sm font-medium">
                </div>

                <div class="text-center pt-2">
                    <button type="submit" name="login" 
                            class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs uppercase tracking-widest px-10 py-3.5 rounded-full transition-all shadow-md shadow-brand-600/10 hover:shadow-lg hover:shadow-brand-600/20 active:scale-95">
                        MASUK
                    </button>
                </div>
            </form>

            <a href="../index.php" class="text-xs font-semibold text-slate-400 hover:text-brand-600 transition-colors tracking-wide mt-8">
                Kembali ke Beranda
            </a>
        </div>
        <div class="hidden md:flex flex-col justify-center items-center text-center p-12 bg-gradient-to-br from-brand-500 to-brand-600 text-white relative">

            <div class="absolute inset-y-0 left-0 w-16 bg-white rounded-r-[100px] -ml-8 pointer-events-none hidden md:block"></div>

            <div class="relative z-10 max-w-sm">
                <h3 class="text-3xl font-black tracking-tight mb-4">Hello, Teguh!</h3>
                <p class="text-sm text-brand-100 leading-relaxed mb-8">
                    Kelola Tugas Harian Tanpa Distraksi di TodoSpace
                    hadir untuk menyelesaikan masalah tumpukan tugas yang tidak teratur. Dikembangkan khusus untuk penggunaan pribadi, platform ini mengutamakan kecepatan akses, kejelasan visual, dan struktur data yang intuitif.
                </p>
            </div>
        </div>

    </div>

</body>
</html>