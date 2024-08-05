<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'editUser':
            $userId = $_POST['userId'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            $stmt = $conn->prepare("UPDATE users SET email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("ssi", $email, $role, $userId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
            }

            $stmt->close();
            break;

        case 'deleteUser':
            $userId = $_POST['userId'];

            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
            }

            $stmt->close();
            break;

        case 'editTask':
            $taskId = $_POST['taskId'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $status = $_POST['status'];
            $due_date = $_POST['dueDate'];

            $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, due_date = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $title, $description, $status, $due_date, $taskId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update task']);
            }

            $stmt->close();
            break;

        case 'deleteTask':
            $taskId = $_POST['taskId'];

            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $taskId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete task']);
            }

            $stmt->close();
            break;

        case 'addCategory':
            $categoryName = $_POST['categoryName'];

            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $categoryName);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add category']);
            }

            $stmt->close();
            break;

        case 'editCategory':
            $categoryId = $_POST['categoryId'];
            $name = $_POST['name'];

            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $categoryId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update category']);
            }

            $stmt->close();
            break;

        case 'deleteCategory':
            $categoryId = $_POST['categoryId'];

            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $categoryId);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete category']);
            }

            $stmt->close();
            break;

        case 'sendNotification':
            $message = $_POST['notificationMessage'];

            // Add code to send notifications to users
            // This could be done via email, in-app notifications, etc.
            // For simplicity, let's just simulate sending notifications
            echo json_encode(['status' => 'success', 'message' => 'Notification sent successfully']);
            break;

        default:
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    switch ($action) {
        case 'getUsers':
            $result = $conn->query("SELECT id, username, email, role FROM users");
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($users);
            break;

        case 'getTasks':
            $result = $conn->query("SELECT id, title, description, status, due_date FROM tasks");
            $tasks = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($tasks);
            break;

        case 'getCategories':
            $result = $conn->query("SELECT id, name FROM categories");
            $categories = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($categories);
            break;

        case 'search':
            $query = $_GET['query'];
            $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE username LIKE ? OR email LIKE ?");
            $searchTerm = "%" . $query . "%";
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            $stmt = $conn->prepare("SELECT id, title, description, status, due_date FROM tasks WHERE title LIKE ? OR description LIKE ?");
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['users' => $users, 'tasks' => $tasks]);
            break;

        case 'generateReports':
            // Add code to generate reports on user activity and task completion
            // For simplicity, let's just return a simple report
            $reportContent = "User Activity Report: \n\n";
            $reportContent .= "Tasks Completed: 10\n";
            $reportContent .= "Tasks Pending: 5\n";
            $reportContent .= "New Users: 3\n";
            echo $reportContent;
            break;

        default:
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

$conn->close();
?>
