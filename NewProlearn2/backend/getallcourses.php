<?php
include 'db.php'; 

header('Content-Type: application/json');


$sql = "SELECT 
            courses.id, 
            courses.title, 
            courses.description, 
            courses.price, 
            CONCAT(users.prenom, ' ', users.nom) AS professor_name, 
            courses.created_at 
        FROM courses
        JOIN users ON courses.professor_id = users.id
        WHERE users.role = 'Professeur'";

$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'price' => $row['price'],
            'professor' => $row['professor_name'],
            'created_at' => $row['created_at']
        ];
    }
}

echo json_encode($courses);
$conn->close();
?>
