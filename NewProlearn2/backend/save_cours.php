<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $professor_id = $_SESSION['user_id'];
    $video_path = '';

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video = $_FILES['video'];
        $allowed_types = ['video/mp4', 'video/avi', 'video/mov']; // Allowed file types
        $upload_dir = __DIR__ . '/uploads/videos/'; // Path relative to your backend
        $max_size = 50 * 1024 * 1024; // Max size: 50MB

        // Validate file type and size
        if (in_array($video['type'], $allowed_types) && $video['size'] <= $max_size) {
            $filename = time() . '_' . basename($video['name']); // Unique filename
            $target_file = $upload_dir . $filename;

            // Create the uploads directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($video['tmp_name'], $target_file)) {
                $video_path = 'uploads/videos/' . $filename; // Relative path for database
            } else {
                die('Failed to upload the video.');
            }
        } else {
            die('Invalid video format or file size too large.');
        }
    }

    // Save course to database
    $sql = "INSERT INTO courses (title, description, price, professor_id, video_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdis', $title, $description, $price, $professor_id, $video_path);
    $stmt->execute();
    $course_id = $stmt->insert_id;
    $stmt->close();

    // Save test questions and options
    if (isset($_POST['test_question'])) {
        $questions = $_POST['test_question'];
        $options = $_POST['test_options'];
        $correct_answers = $_POST['correct_answer'];

        foreach ($questions as $index => $question) {
            $sql = "INSERT INTO tests (course_id, question) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $course_id, $question);
            $stmt->execute();
            $test_id = $stmt->insert_id;
            $stmt->close();

            // Save options for each question
            foreach ($options[$index] as $option_index => $option) {
                $is_correct = ($option_index == $correct_answers[$index]) ? 1 : 0;
                $sql = "INSERT INTO test_options (test_id, option_text, is_correct) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isi', $test_id, $option, $is_correct);
                $stmt->execute();
            }
        }
    }
}
?>
