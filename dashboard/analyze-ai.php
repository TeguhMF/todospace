<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/database.php';
$user_id = $_SESSION['user_id'];

// 1. Ambil semua tugas yang BELUM selesai milik user
$query = "SELECT id, title, description, deadline FROM tasks WHERE user_id = $user_id AND status != 'done'";
$result = mysqli_query($conn, $query);

$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = [
        'id' => $row['id'],
        'judul' => $row['title'],
        'deskripsi' => $row['description'],
        'deadline' => $row['deadline']
    ];
}

// Jika tidak ada tugas aktif, langsung balik ke dashboard
if (empty($tasks)) {
    header("Location: index.php?status=notasks");
    exit;
}

// 2. Siapkan Prompt untuk AI
$tanggal_sekarang = date('Y-m-d');
$prompt = "Kamu adalah Asisten Akademik AI Pintar untuk Mahasiswa Informatika. Tugasmu adalah menganalisis daftar tugas kuliah berikut dan menentukan prioritasnya ('Tinggi', 'Sedang', atau 'Rendah') berdasarkan kedekatan deadline (hari ini: $tanggal_sekarang) dan kompleksitas deskripsinya (misal: proyek koding/basis data lebih tinggi prioritasnya dibanding rangkuman materi).

Berikan output HARUS dalam bentuk JSON array murni tanpa markdown, tanpa backticks (```json ... ```), formatnya seperti ini:
[
  {\"id\": 1, \"priority\": \"Tinggi\", \"insight\": \"Saran analisis pendek di sini...\"}
]

Berikut adalah daftar tugasnya:\n" . json_encode($tasks, JSON_PRETTY_PRINT);

// Ambil jalur file .env di folder root (naik satu folder dari folder dashboard)
$envPath = __DIR__ . '/../.env';
$apiKey = '';

if (file_exists($envPath)) {
    // Membaca file baris demi baris
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Abaikan jika baris berupa komentar
        if (strpos(trim($line), '#') === 0) continue;
        
        // Pisahkan key dan value berdasarkan tanda sama dengan (=)
        list($name, $value) = explode('=', $line, 2);
        if (trim($name) === 'GEMINI_API_KEY') {
            $apiKey = trim($value);
            break;
        }
    }
}

$ch = curl_init($apiKey ? 'https://api.generativeai.google/v1beta2/models/text-bison-001:generateMessage' : '');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'prompt' => [
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ]
    ],
    'temperature' => 0.7,
    'maxOutputTokens' => 500
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

// 4. Proses Response AI dan Update Database
if ($response) {
    $responseArr = json_decode($response, true);
    $textResult = $responseArr['candidates'][0]['content']['parts'][0]['text'] ?? '';
    
    // Bersihkan teks jika AI tidak sengaja menyertakan block format markdown
    $textResult = trim(str_replace(['```json', '```'], '', $textResult));
    $aiAnalysis = json_decode($textResult, true);

    if (is_array($aiAnalysis)) {
        foreach ($aiAnalysis as $item) {
            $id = (int)$item['id'];
            $priority = mysqli_real_escape_string($conn, $item['priority']);
            $insight = mysqli_real_escape_string($conn, $item['insight']);

            $updateQuery = "UPDATE tasks SET ai_priority = '$priority', ai_insight = '$insight' WHERE id = $id AND user_id = $user_id";
            mysqli_query($conn, $updateQuery);
        }
    }
}

header("Location: index.php?status=aisynced");
exit;