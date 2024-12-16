<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}



// Get user data from the session
$response = [
    'prenom' => $_SESSION['prenom'],
    'nom' => $_SESSION['nom']
];

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($response);



