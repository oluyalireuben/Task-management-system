<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$role = $_SESSION['role'];

if ($role != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $name = $_POST['name'];

        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Category created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create category']);
        }

        $stmt->close();
    } elseif ($action == 'update') {
        $category_id = $_POST['category_id'];
        $name = $_POST['name'];

        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $category_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update category']);
        }

        $stmt->close();
    } elseif ($action == 'delete') {
        $category_id = $_POST['category_id'];

        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $category_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete category']);
        }

        $stmt->close();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare("SELECT id, name FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode(['status' => 'success', 'categories' => $categories]);

    $stmt->close();
}
?>
