<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db_name = "todospace"; // Sesuaikan dengan nama databasemu

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM tasks ORDER BY id DESC";
        $result = $conn->query($sql);
        $tasks = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Pengecekan aman untuk kolom status/is_completed
                $isCompleted = false;
                if (isset($row['is_completed'])) {
                    $isCompleted = (bool)$row['is_completed'];
                } elseif (isset($row['status'])) {
                    $isCompleted = ($row['status'] == 'completed' || $row['status'] == 1 || $row['status'] == '1');
                } elseif (isset($row['completed'])) {
                    $isCompleted = (bool)$row['completed'];
                }

                $tasks[] = [
                    'id' => (string)$row['id'],
                    'title' => $row['title'] ?? '',
                    'description' => $row['description'] ?? '',
                    'is_completed' => $isCompleted,
                    'due_date' => $row['created_at'] ?? $row['due_date'] ?? date('Y-m-d H:i:s')
                ];
            }
        }
        echo json_encode(['data' => $tasks]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $conn->real_escape_string($data['title'] ?? '');
        $description = isset($data['description']) ? $conn->real_escape_string($data['description']) : '';

        // Sesuaikan kolom jika di database bukan is_completed
        $sql = "INSERT INTO tasks (title, description) VALUES ('$title', '$description')";
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
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $is_completed = !empty($data['is_completed']) ? 1 : 0;

        if ($id > 0) {
            // Coba update ke is_completed, jika kolom di DB bernama 'status'/'completed' sesuaikan di sini
            $sql = "UPDATE tasks SET is_completed = $is_completed WHERE id = $id";
            if (!$conn->query($sql)) {
                // Fallback jika nama kolomnya 'status'
                $statusVal = $is_completed ? 'completed' : 'pending';
                $conn->query("UPDATE tasks SET status = '$statusVal' WHERE id = $id");
            }

            $res = $conn->query("SELECT * FROM tasks WHERE id = $id");
            if ($res && $row = $res->fetch_assoc()) {
                echo json_encode([
                    'data' => [
                        'id' => (string)$row['id'],
                        'title' => $row['title'] ?? '',
                        'description' => $row['description'] ?? '',
                        'is_completed' => (bool)$is_completed,
                        'due_date' => $row['created_at'] ?? date('Y-m-d H:i:s')
                    ]
                ]);
            }
        }
        break;

    case 'DELETE':
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