<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register.html');
    exit();
}

// Include database connection
include '../backend/db.php';

// Check if course_id is provided in the URL
if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Fetch course details from the database
    $sql = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if course exists
    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    } else {
        $course = null;
    }

    $stmt->close();
} else {
    $course = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link rel="stylesheet" href="../css/course_details.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="course-details-container">
        <?php if ($course): ?>
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($course['price'], 2); ?></p>

            <!-- Button to start or play the course -->
            <form action="play_course.php" method="GET">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <button type="submit" class="play-course-button">Play Course</button>
            </form>

        <?php else: ?>
            <p>Sorry, the course you're looking for does not exist.</p>
        <?php endif; ?>

        <br>
        <a href="mes_etudiant_courses.php">Back to My Courses</a>
    </div>
</body>
</html>
