<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Koneksi ke Database MySQL milikmu
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "todospace_db"; // Sesuaikan dengan nama database-mu

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["message" => "Koneksi gagal: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ambil semua task
        $sql = "SELECT * FROM tasks ORDER BY id DESC";
        $result = $conn->query($sql);
        $tasks = [];

        while ($row = $result->fetch_assoc()) {
            // Samakan key JSON dengan model di Flutter
            $tasks[] = [
                'id' => (string)$row['id'],
                'title' => $row['title'],
                'description' => $row['description'] ?? '',
                'is_completed' => (bool)$row['is_completed'],
                'due_date' => $row['created_at'] ?? date('Y-m-d H:i:s')
            ];
        }
        echo json_encode(['data' => $tasks]);
        break;

    case 'POST':
        // Tambah task baru
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $conn->real_escape_string($data['title']);
        $description = isset($data['description']) ? $conn->real_escape_string($data['description']) : '';

        $sql = "INSERT INTO tasks (title, description, is_completed) VALUES ('$title', '$description', 0)";
        if ($conn->query($sql)) {
            $insert_id = $conn->insert_id;
            echo json_encode([
                'data' => [
                    'id' => (string)$insert_id,
                    'title' => $title,
                    'description' => $description,
                    'is_completed' => false,
                    'due_date' => date('Y-m-d H:i:s')
                ]
            ]);
        }
        break;

    case 'PUT':
        // Update status is_completed
        $data = json_decode(file_get_contents("php://input"), true);
        // Ambil ID dari URL (misal: /api/tasks.php?id=1) atau parsing PATH_INFO
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $is_completed = !empty($data['is_completed']) ? 1 : 0;

        if ($id > 0) {
            $sql = "UPDATE tasks SET is_completed = $is_completed WHERE id = $id";
            if ($conn->query($sql)) {
                // Return data yang baru diupdate
                $res = $conn->query("SELECT * FROM tasks WHERE id = $id");
                $row = $res->fetch_assoc();
                echo json_encode([
                    'data' => [
                        'id' => (string)$row['id'],
                        'title' => $row['title'],
                        'description' => $row['description'] ?? '',
                        'is_completed' => (bool)$row['is_completed'],
                        'due_date' => $row['created_at'] ?? date('Y-m-d H:i:s')
                    ]
                ]);
            }
        }
        break;

    case 'DELETE':
        // Hapus task
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $sql = "DELETE FROM tasks WHERE id = $id";
            if ($conn->query($sql)) {
                echo json_encode(["message" => "Task berhasil dihapus"]);
            }
        }
        break;
}

$conn->close();
?>