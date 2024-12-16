<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // If logged in, return the user data (role, logged_in status)
    echo json_encode([
        'logged_in' => true,
        'role' => $_SESSION['role']
    ]);
} else {
    // If not logged in, return a logged_in status of false
    echo json_encode([
        'logged_in' => false
    ]);
}
?>
