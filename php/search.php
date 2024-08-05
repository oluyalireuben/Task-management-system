<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$search_term = $_GET['term'];

if ($_SESSION['role'] == 'admin') {
    $stmt = $conn->prepare("SELECT tasks.id, users.username, tasks.title, tasks.description, tasks.due_date, tasks.status, categories.name AS category FROM tasks LEFT JOIN users ON tasks.user_id = users.id LEFT JOIN categories ON tasks.category_id = categories.id WHERE tasks.title LIKE ? OR tasks.description LIKE ?");
    $search_term = '%' . $search_term . '%';
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT tasks.id, tasks.title, tasks.description, tasks.due_date, tasks.status, categories.name AS category FROM tasks LEFT JOIN categories ON tasks.category_id = categories.id WHERE tasks.user_id = ? AND (tasks.title LIKE ? OR tasks.description LIKE ?)");
    $search_term = '%' . $search_term . '%';
    $stmt->bind_param("iss", $user_id, $search_term, $search_term);
}

$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode(['status' => 'success', 'tasks' => $tasks]);

$stmt->close();
?>
