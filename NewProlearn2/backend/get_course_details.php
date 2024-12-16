<?php
session_start();
include '../backend/db.php';

header('Content-Type: application/json');

if (!isset($_GET['course_id'])) {
    echo json_encode(['error' => 'Course ID is required.']);
    exit();
}

$course_id = intval($_GET['course_id']);

// Fetch course details from the database
$sql = "SELECT courses.id, courses.title, courses.description, courses.price, courses.professor_id, users.prenom, users.nom 
        FROM courses 
        JOIN users ON courses.professor_id = users.id 
        WHERE courses.id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        echo json_encode($course);
    } else {
        echo json_encode(['error' => 'Course not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Failed to prepare the SQL statement.']);
}

$conn->close();
