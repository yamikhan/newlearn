<?php
session_start();
include '../backend/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professeur') {
    header('Location: ../login/login.php');
    exit();
}


$professor_id = $_SESSION['user_id'];


$sql = "SELECT id, title, description, price, created_at FROM courses WHERE professor_id = ?";
$courses = [];

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    $stmt->close();
}

$conn->close();


header('Content-Type: application/json');
echo json_encode($courses);
?>
