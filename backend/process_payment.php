<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../register.html');
    exit();
}

// Include database connection
include '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];
    $payment_method = $_POST['payment_method'];

    // Validate required fields
    if (empty($course_id) || empty($payment_method)) {
        echo "Missing required payment details.";
        exit();
    }

    // Fetch the course details (price) from the database
    $sql = "SELECT title, price FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid course ID.";
        exit();
    }

    $course = $result->fetch_assoc();
    $course_title = $course['title'];
    $amount = $course['price'];

    $stmt->close();

    // Initialize variables for additional payment details
    $paypal_email = null;
    $card_number = null;
    $card_expiry = null;
    $card_cvc = null;
    $skrill_email = null;

    // Handle additional payment method details
    if ($payment_method === 'PayPal') {
        $paypal_email = $_POST['paypal_email'] ?? '';
        if (empty($paypal_email) || !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid PayPal email address.";
            exit();
        }
    } elseif ($payment_method === 'Credit Card') {
        $card_number = $_POST['card_number'] ?? '';
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvc = $_POST['card_cvc'] ?? '';

        if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) {
            echo "Invalid credit card number.";
            exit();
        }
        if (empty($card_expiry) || !preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $card_expiry)) {
            echo "Invalid card expiry date. Use MM/YY format.";
            exit();
        }
        if (empty($card_cvc) || !preg_match('/^\d{3,4}$/', $card_cvc)) {
            echo "Invalid CVC code.";
            exit();
        }
    } elseif ($payment_method === 'Skrill') {
        $skrill_email = $_POST['skrill_email'] ?? '';
        if (empty($skrill_email) || !filter_var($skrill_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid Skrill email address.";
            exit();
        }
    } else {
        echo "Invalid payment method.";
        exit();
    }

    // Simulate payment processing (no database storage)
    echo "<h1>Payment Confirmation</h1>";
    echo "<p>Thank you for your payment!</p>";
    echo "<p>Course: <strong>$course_title</strong></p>";
    echo "<p>Amount: <strong>\$$amount</strong></p>";
    echo "<p>Payment Method: <strong>$payment_method</strong></p>";

    // Display additional details for the chosen payment method
    if ($payment_method === 'PayPal') {
        echo "<p>PayPal Email: <strong>$paypal_email</strong></p>";
    } elseif ($payment_method === 'Credit Card') {
        echo "<p>Credit Card: <strong>**** **** **** " . substr($card_number, -4) . "</strong></p>";
    } elseif ($payment_method === 'Skrill') {
        echo "<p>Skrill Email: <strong>$skrill_email</strong></p>";
    }

    echo "<a href='../dashboard/dashboard_etudiant.html'>Go to Dashboard</a>";
} else {
    echo "Invalid request method.";
}
?>
