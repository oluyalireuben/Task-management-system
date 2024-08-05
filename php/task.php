<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'addTask') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $category_id = $_POST['category'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, category_id, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $description, $due_date, $category_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Task addition failed']);
        }

        $stmt->close();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];

    if ($action == 'getUserTasks') {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT tasks.id, tasks.title, tasks.description, tasks.due_date, categories.name as category 
                                FROM tasks 
                                JOIN categories ON tasks.category_id = categories.id 
                                WHERE tasks.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);

        $stmt->close();
    } elseif ($action == 'getCategories') {
        $result = $conn->query("SELECT id, name FROM categories");
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode($categories);
    } elseif ($action == 'deleteTask') {
        $task_id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Task deletion failed']);
        }

        $stmt->close();
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$conn->close();
?>
