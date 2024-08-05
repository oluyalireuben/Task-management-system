<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
        }

        $stmt->close();
    } elseif ($action == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        error_log("Login attempt for username: $username");

        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['user_id'] = $id;
                $_SESSION['role'] = $role;
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'role' => $role]);
            } else {
                error_log("Invalid password for username: $username");
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }
        } else {
            error_log("User not found for username: $username");
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }

        $stmt->close();
    }
}
?>
