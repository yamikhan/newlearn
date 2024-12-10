<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $professor_id = $_SESSION['user_id']; 

    $sql = "INSERT INTO courses (title, description, price, professor_id) VALUES (?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssdi', $title, $description, $price, $professor_id);
        if ($stmt->execute()) {
            echo "Cours créé avec succès!";
        } else {
            echo "Erreur lors de la création du cours: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur SQL: " . $conn->error;
    }

    $conn->close();
}
?>
