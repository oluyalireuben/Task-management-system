<?php
include 'db.php';

header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action == 'getUsers') {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403); // Forbidden
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $result = $conn->query("SELECT id, username, email, role FROM users");
        if ($result) {
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($users);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch users']);
        }

    } elseif ($action == 'getTasks') {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $result = $conn->query("SELECT * FROM tasks");
        if ($result) {
            $tasks = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($tasks);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch tasks']);
        }

    } elseif ($action == 'getCategories') {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $result = $conn->query("SELECT * FROM categories");
        if ($result) {
            $categories = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($categories);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch categories']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'addCategory') {
        $categoryName = isset($_POST['categoryName']) ? $_POST['categoryName'] : '';
        if (empty($categoryName)) {
            echo json_encode(['status' => 'error', 'message' => 'Category name is required']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $categoryName);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add category']);
        }

        $stmt->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
