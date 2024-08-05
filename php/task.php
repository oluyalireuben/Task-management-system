<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'addTask') {
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $status = 'Pending'; // Default status
        $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : 'Not set';
        $user_id = $_SESSION['user_id'];

        if (empty($title) || empty($description)) {
            echo json_encode(['status' => 'error', 'message' => 'Title and description are required']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, due_date, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $status, $due_date, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add task']);
        }

        $stmt->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action == 'getTasks') {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT title, description, status, due_date FROM tasks WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $tasks = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($tasks);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch tasks']);
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
