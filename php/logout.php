<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page
header('Location: http://localhost/projects/Task_Management_App/login.html');
exit();
?>



