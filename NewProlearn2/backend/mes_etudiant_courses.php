<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: ../register.html');
    exit();
}


include '../backend/db.php';

$user_id = $_SESSION['user_id'];


$sql = "
    SELECT c.id, c.title, c.description, c.price
    FROM courses c
    JOIN payments p ON c.id = p.course_id
    WHERE p.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($course = $result->fetch_assoc()) {
    $courses[] = $course;
}

$stmt->close();
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Paid Courses</title>
    <link rel="stylesheet" href="../css/mes_etudiant_courses.css"> 
</head>
<body>
    <h1>My Paid Courses</h1>

    <div id="courses-list">
        <?php if (empty($courses)): ?>
            <p>You haven't paid for any courses yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($courses as $course): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                        <p><?php echo htmlspecialchars($course['description']); ?></p>
                        <p>Price: $<?php echo number_format($course['price'], 2); ?></p>
                        <a href="course_details.php?course_id=<?php echo $course['id']; ?>">View Course Details</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <br>
    <a href="../dashboard/dashboard_etudiant.html">Back to Dashboard</a>
</body>
</html>
